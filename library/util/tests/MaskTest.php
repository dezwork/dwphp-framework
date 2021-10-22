<?php

use util\Mask;
use PHPUnit\Framework\TestCase;
/**
 * @Author: Cleberson Bieleski
 * @Date:   2018-03-14 15:52:50
 * @Last Modified by:   Cleberson Bieleski
 * @Last Modified time: 2018-03-15 21:04:21
 */
class MaskTest extends TestCase{


    /**
    * @covers util\Mask::mask()
    */
    public function testMask() {
        $this->assertFalse((new Mask)->mask('13779426000137', 'UNDEFINED_CONST'));
        $this->assertFalse((new Mask)->mask('13779426000137'));
        $this->assertFalse((new Mask)->mask('',''));
        $this->assertEquals('13.779.426/0001-37', (new Mask)->mask('13779426000137', Mask::DOCUMENTO));
        $this->assertEquals('13.779.426/0001-37', (new Mask)->mask('13779426000137', Mask::CNPJ));
        $this->assertEquals('732.584.423-98', (new Mask)->mask('73258442398', Mask::DOCUMENTO));
        $this->assertEquals('732.584.423-98', (new Mask)->mask('73258442398', Mask::CPF));
        $this->assertEquals('31.030-080', (new Mask)->mask('31030080', Mask::CEP));
        $this->assertEquals('(31)3072-7066', (new Mask)->mask('3130727066', Mask::TELEFONE));
        $this->assertEquals('(31)99503-7066', (new Mask)->mask('31995037066', Mask::TELEFONE));
        $this->assertEquals('a1:b2:c3:d4:e5:f6', (new Mask)->mask('a1b2c3d4e5f6', Mask::MAC));
    }

    /**
    * @covers util\Mask::maskFactory()
    */
    public function testMaskFactory() {
        $this->assertFalse((new Mask)->maskFactory('13.779.426/0001-37',''));
        $this->assertFalse((new Mask)->maskFactory('', Mask::DOCUMENTO));
        $this->assertFalse((new Mask)->maskFactory('13.779.426/0001-37','##'));

        $this->assertEquals('13.779.426/0001-37', (new Mask)->maskFactory('13779426000137', Mask::CNPJ));
        $this->assertEquals('13.779.426/0001-37', (new Mask)->maskFactory('13779426000137', Mask::CNPJ));
    }

    /**
    * @covers util\Mask::unmask()
    */
    public function testUnmask() {
        $this->assertEquals('73258442398', (new Mask)->unmask('732.584.423-98'));
        $this->assertEquals('a1b2c3d4e5f6', (new Mask)->unmask('a1:b2:c3:d4:e5:f6'));
    }

}