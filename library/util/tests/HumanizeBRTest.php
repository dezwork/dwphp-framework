<?php
/**
 * @Author: Cleberson Bieleski
 * @Date:   2018-03-14 15:52:50
 * @Last Modified by:   Cleber
 * @Last Modified time: 30-03-2018 19:50:17
 */

use util\HumanizeBR;
use util\Date;
use PHPUnit\Framework\TestCase;
class HumanizeBRTest extends TestCase{


    /*
    * @covers util\HumanizeBR::NumberToWords()
    */
    public function testNumberToWords(){
        $this->assertFalse((new HumanizeBR())->numberToWords('teste'));
        $this->assertFalse((new HumanizeBR())->numberToWords('99999999999999999999'));
        $this->assertEquals('um', (new HumanizeBR())->numberToWords('1'));
        $this->assertEquals('menos um', (new HumanizeBR())->numberToWords('-1'));
        $this->assertEquals('dez', (new HumanizeBR())->numberToWords('10'));
        $this->assertEquals('um mil', (new HumanizeBR())->numberToWords('1000'));
        $this->assertEquals('dezenove', (new HumanizeBR())->numberToWords('19'));
        $this->assertEquals('vinte e cinco', (new HumanizeBR())->numberToWords('25'));
        $this->assertEquals('cem', (new HumanizeBR())->numberToWords('100'));
        $this->assertEquals('cento e onze', (new HumanizeBR())->numberToWords('111'));
        $this->assertEquals('cento e noventa e nove', (new HumanizeBR())->numberToWords('199'));
        $this->assertEquals('duzentos e setenta e seis', (new HumanizeBR())->numberToWords('276'));
    	$this->assertEquals('um mil, novecentos e noventa e dois', (new HumanizeBR())->numberToWords('1992'));
        $this->assertEquals('cento e vinte e três milhões, quatrocentos e cinquenta e seis mil, setecentos e oitenta e nove', (new HumanizeBR())->numberToWords('123456789'));
        $this->assertEquals('um bilhão', (new HumanizeBR())->numberToWords('1000000000'));
        $this->assertEquals('cento e vinte ponto trinta e cinco', (new HumanizeBR())->numberToWords('120.35'));
        $this->assertEquals('noventa ponto nove', (new HumanizeBR())->numberToWords('90,9'));
        $this->assertEquals('vinte metros', (new HumanizeBR())->numberToWords('20', 'metros'));
        $this->assertEquals('quinze reais', (new HumanizeBR())->numberToWords('15', 'reais'));
	}

    /*
    * @covers util\HumanizeBR::humanTimeDiff()
    */
    public function testHumanTimeDiff(){
        $this->assertEquals( '1 segundo atrás', (new HumanizeBR())->humanTimeDiff( time() - 1 ) );
        $this->assertEquals( '30 segundos atrás', (new HumanizeBR())->humanTimeDiff( time() - 30 ) );
        $this->assertEquals( '1 minuto atrás', (new HumanizeBR())->humanTimeDiff( time() - ( util\DateTime::SECONDS_IN_A_MINUTE * 1.4 ) ) );
        $this->assertEquals( '5 minutos atrás', (new HumanizeBR())->humanTimeDiff( time() - ( util\DateTime::SECONDS_IN_A_MINUTE * 5 ) ) );
        $this->assertEquals( '1 hora atrás', (new HumanizeBR())->humanTimeDiff( time() - ( util\DateTime::SECONDS_IN_AN_HOUR ) ) );
        $this->assertEquals( '2 horas atrás', (new HumanizeBR())->humanTimeDiff( time() - ( util\DateTime::SECONDS_IN_AN_HOUR * 2 ) ) );
        $this->assertEquals( '1 dia atrás', (new HumanizeBR())->humanTimeDiff( time() - ( util\DateTime::SECONDS_IN_AN_HOUR * 24 ) ) );
        $this->assertEquals( '5 dias atrás', (new HumanizeBR())->humanTimeDiff( time() - ( util\DateTime::SECONDS_IN_AN_HOUR * 24 * 5 ) ) );
        $this->assertEquals( '1 semana atrás', (new HumanizeBR())->humanTimeDiff( time() - ( util\DateTime::SECONDS_IN_AN_HOUR * 24 * 7 ) ) );
        $this->assertEquals( '2 semanas atrás', (new HumanizeBR())->humanTimeDiff( time() - ( util\DateTime::SECONDS_IN_AN_HOUR * 24 * 14 ) ) );
        $this->assertEquals( '1 mês atrás', (new HumanizeBR())->humanTimeDiff( time() - ( util\DateTime::SECONDS_IN_A_WEEK * 5 ) ) );
        $this->assertEquals( '2 meses atrás', (new HumanizeBR())->humanTimeDiff( time() - ( util\DateTime::SECONDS_IN_A_WEEK * 10 ) ) );
        $this->assertEquals( '1 ano atrás', (new HumanizeBR())->humanTimeDiff( time() - ( util\DateTime::SECONDS_IN_A_MONTH * 15 ) ) );
        $this->assertEquals( '2 anos atrás', (new HumanizeBR())->humanTimeDiff( time() - ( util\DateTime::SECONDS_IN_A_MONTH * 36 ) ) );
        $this->assertEquals( '11 anos atrás', (new HumanizeBR())->humanTimeDiff( time() - ( util\DateTime::SECONDS_IN_A_MONTH * 140 ) ) );
        $this->assertEquals( 'onze anos atrás', (new HumanizeBR())->humanTimeDiff( time() - ( util\DateTime::SECONDS_IN_A_MONTH * 140 ), '', true ) );
    }



}