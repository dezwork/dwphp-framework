<?php

use util\Monetary;

use PHPUnit\Framework\TestCase;
/**
 * @Author: Cleberson Bieleski
 * @Date:   2018-03-14 15:52:50
 * @Last Modified by:   Cleber
 * @Last Modified time: 25-06-2018 17:16:56
 */
class MonetaryTest extends TestCase{


    /**
    * @covers util\Monetary::__construct()
    * @covers util\Monetary::setmoney()
    */
    public function testNew() {

        $this->assertEquals( '60050' , (new Monetary('60050'))->getMoney()->getAmount() );

    }

    /**
    * @covers util\Monetary::add()
    * @covers util\Monetary::getMoney()
    */
    public function testAdd() {
        $value1 = new Monetary('20050');
        $value2 = new Monetary('40000');
        $value3 = new Monetary('123400');
        $value4 = new Monetary('-50000');

        $this->assertEquals( (new Monetary('60050'))->getMoney()->getAmount() , $value1->add($value2)->getMoney()->getAmount() );
        $this->assertEquals( (new Monetary('183450'))->getMoney()->getAmount() , $value3->add($value1)->getMoney()->getAmount() );
        $this->assertEquals( (new Monetary('-10000'))->getMoney()->getAmount() , $value2->add($value4)->getMoney()->getAmount() );

    }

    /**
    * @covers util\Monetary::subtract()
    */
    public function testSubtract() {
        $value1 = new Monetary('20050');
        $value2 = new Monetary('40203');
        $value3 = new Monetary('123400');

        $this->assertEquals( (new Monetary('20153'))->getMoney()->getAmount() , $value2->subtract($value1)->getMoney()->getAmount() );
        $this->assertEquals( (new Monetary('103350'))->getMoney()->getAmount() , $value3->subtract($value1)->getMoney()->getAmount() );

    }

    /**
    * @covers util\Monetary::multiply()
    */
    public function testMultiply() {
        $value1 = new Monetary('10025');
        $value2 = new Monetary('40203');
        $value3 = new Monetary('123400');

        $this->assertEquals( (new Monetary('20050'))->getMoney()->getAmount() , $value1->multiply(2)->getMoney()->getAmount() );
        $this->assertEquals( (new Monetary('4961050200'))->getMoney()->getAmount() , $value3->multiply($value2->getMoney()->getAmount())->getMoney()->getAmount() );

    }

    /**
    * @covers util\Monetary::divide()
    */
    public function testDivide() {
        $value1 = new Monetary('1000');
        $value2 = new Monetary('33340');

        $this->assertEquals( (new Monetary('500'))->getMoney()->getAmount() , $value1->divide(2)->getMoney()->getAmount() );
        $this->assertEquals( (new Monetary('5557'))->getMoney()->getAmount() , $value2->divide(6)->getMoney()->getAmount() );

    }

    /**
    * @covers util\Monetary::moneyDb()
    */
    public function testMoneyDb() {
        $this->assertEquals( '0.01'     , (new Monetary('1'))->moneyDb() );
        $this->assertEquals( '0.31'     , (new Monetary('31'))->moneyDb() );
        $this->assertEquals( '1.99'     , (new Monetary('199'))->moneyDb() );
        $this->assertEquals( '12.30'    , (new Monetary('1230'))->moneyDb() );
        $this->assertEquals( '123.45'   , (new Monetary('12345'))->moneyDb() );
        $this->assertEquals( '200.50'   , (new Monetary('20050'))->moneyDb() );
        $this->assertEquals( '87654.32' , (new Monetary('8765432'))->moneyDb() );
    }

    /**
    * @covers util\Monetary::moneyDb()
    */
    public function testMoneyDbNegative() {
        $this->assertEquals( '-0.01'     , (new Monetary('-1'))->moneyDb() );
        $this->assertEquals( '-0.31'     , (new Monetary('-31'))->moneyDb() );
        $this->assertEquals( '-1.99'     , (new Monetary('-199'))->moneyDb() );
        $this->assertEquals( '-12.30'    , (new Monetary('-1230'))->moneyDb() );
        $this->assertEquals( '-123.45'   , (new Monetary('-12345'))->moneyDb() );
        $this->assertEquals( '-200.50'   , (new Monetary('-20050'))->moneyDb() );
        $this->assertEquals( '-87654.32' , (new Monetary('-8765432'))->moneyDb() );
    }

    /**
    * @covers util\Monetary::moneyBR()
    */
    public function testMoneyBR() {
        $this->assertEquals( 'R$ 0,01'     , (new Monetary('1'))->moneyBR() );
        $this->assertEquals( 'R$ 0,31'     , (new Monetary('31'))->moneyBR() );
        $this->assertEquals( 'R$ 1,99'     , (new Monetary('199'))->moneyBR() );
        $this->assertEquals( 'R$ 12,30'    , (new Monetary('1230'))->moneyBR() );
        $this->assertEquals( 'R$ 123,45'   , (new Monetary('12345'))->moneyBR() );
        $this->assertEquals( 'R$ 200,50'   , (new Monetary('20050'))->moneyBR() );
        $this->assertEquals( 'R$ 87.654,32' , (new Monetary('8765432'))->moneyBR() );
    }

    /**
    * @covers util\Monetary::moneyUS()
    */
    public function testMoneyUS() {
        $this->assertEquals( '$ 0.01'     , (new Monetary('1'))->moneyUS() );
        $this->assertEquals( '$ 0.31'     , (new Monetary('31'))->moneyUS() );
        $this->assertEquals( '$ 1.99'     , (new Monetary('199'))->moneyUS() );
        $this->assertEquals( '$ 12.30'    , (new Monetary('1230'))->moneyUS() );
        $this->assertEquals( '$ 123.45'   , (new Monetary('12345'))->moneyUS() );
        $this->assertEquals( '$ 200.50'   , (new Monetary('20050'))->moneyUS() );
        $this->assertEquals( '$ 87,654.32' , (new Monetary('8765432'))->moneyUS() );
    }

    /**
    * @covers util\Monetary::percentage()
    */
    public function testPercentage() {
        $this->assertEquals( (new Monetary('22500'))->getMoney()    , (new Monetary('15000'))->percentage('50') );
        $this->assertEquals( (new Monetary('7500'))->getMoney()     , (new Monetary('15000'))->percentage('-50') );
        $this->assertEquals( (new Monetary('11112345'))->getMoney() , (new Monetary('12345678'))->percentage('-9.99') );
        $this->assertEquals( (new Monetary('1739'))->getMoney()     , (new Monetary('1800'))->percentage('-3.4') );
    }
}