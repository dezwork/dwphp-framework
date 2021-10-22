<?php
/**
 * @Author: Cleberson Bieleski
 * @Date:   2018-03-14 15:52:50
 * @Last Modified by:   Cleber
 * @Last Modified time: 21-03-2018 11:33:29
 */

use util\Measure;
use PHPUnit\Framework\TestCase;
class MeasuresTest extends TestCase{
    /*
    * @covers util\Measure::sizeFormat()
    */
    public function testSizeFormat() {
        $this->assertEquals('456 B', (new Measure)->sizeFormat('456' , 0));
        $this->assertEquals('456.00 B', (new Measure)->sizeFormat('456' , 2));
        $this->assertEquals('1 KB', (new Measure)->sizeFormat('1024' , 0));
        $this->assertEquals('1.00 KB', (new Measure)->sizeFormat('1024' , 2));
        $this->assertEquals('8 MB', (new Measure)->sizeFormat('8388608' , 0));
        $this->assertEquals('8.00 MB', (new Measure)->sizeFormat('8388608' , 2));
        $this->assertEquals('8.39 MB', (new Measure)->sizeFormat('8799908' , 2));
        $this->assertEquals('8 GB', (new Measure)->sizeFormat('8589934592' , 0));
        $this->assertEquals('8.00 GB', (new Measure)->sizeFormat('8589934592' , 2));
        $this->assertEquals('54.00 GB', (new Measure)->sizeFormat('57982058496' , 2));
        $this->assertEquals('1 TB', (new Measure)->sizeFormat('1099511627776' , 0));
        $this->assertEquals('1.00 TB', (new Measure)->sizeFormat('1099511627776' , 2));
        $this->assertEquals('1 PB', (new Measure)->sizeFormat('1125899906842624' , 0));
        $this->assertEquals('1.00 PB', (new Measure)->sizeFormat('1125899906842624' , 2));
        $this->assertEquals('1024 PB', (new Measure)->sizeFormat('1152921504606847000' , 0));
        $this->assertEquals('1024.00 PB', (new Measure)->sizeFormat('1152921504606847000' , 2));
    }

    /**
    * @covers util\Measure::convertMemorySize()
    */
    public function testConvertMemorySize() {
        $this->assertEquals('1 KB', (new Measure)->convertMemorySize('1024' , 'K'));
        $this->assertEquals('5452595.2 B', (new Measure)->convertMemorySize('5.2 Mb' , 'b'));
        $this->assertEquals('10240 B', (new Measure)->convertMemorySize('10 kilobytes' , 'bytes'));
        $this->assertEquals('2 KB', (new Measure)->convertMemorySize(2048 , 'k'));
        $this->assertEquals('1024 MB', (new Measure)->convertMemorySize('1024M' , 'MB'));
        $this->assertEquals('1024 GB', (new Measure)->convertMemorySize('1TB' , 'GB'));
    }


}