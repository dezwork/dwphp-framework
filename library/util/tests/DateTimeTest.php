<?php
/**
 * @Author: Cleberson Bieleski
 * @Date:   2018-03-14 15:52:50
 * @Last Modified by:   Cleber
 * @Last Modified time: 02-04-2018 09:06:55
 */

use util\DateTime;
use PHPUnit\Framework\TestCase;
class DateTimeTest extends TestCase{


    /*
    * @covers util\DateTime::dateBR()
    */
    public function testDateBR(){
        $this->assertEquals(new \DateTime('1992-08-14'),(new DateTime('14/08/1992'))->getDateOBJ());
	}

}