<?php
/**
 * @Author: Cleberson Bieleski
 * @Date:   2018-03-14 15:52:50
 * @Last Modified by:   Cleber
 * @Last Modified time: 21-03-2018 11:51:22
 */

use util\Server;
use PHPUnit\Framework\TestCase;
class ServerTest extends TestCase{
    /*
    * @covers util\Server::isHttps()
    */
    public function testIsHttps(){
        $_SERVER['HTTPS'] = null;
        $this->assertFalse( (new Server)->isHttps() );
        $_SERVER['HTTPS'] = 'on';
        $this->assertTrue( (new Server)->isHttps() );
    }


}