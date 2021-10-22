<?php

use util\Arrays;
use PHPUnit\Framework\TestCase;
/**
 * @Author: Cleber
 * @Date:   2018-03-14 15:52:50
 * @Last Modified by:   Cleberson Bieleski
 * @Last Modified time: 2018-03-15 13:58:43
 */
class ArraysTest extends TestCase{


    /**
    * @covers util\Arrays::fastArrayUnique()
    */
	public function testFastArrayUnique(){

        $array = array(10, 100, 1231, 10, 600, 20, 40, 1231, 20, 6, 1);
        $this->assertEquals(array(10, 100, 1231, 600, 20, 40, 6, 1),  (new Arrays())->fastArrayUnique($array));

        $array = array('hello', 'world', 'this', 'is', 'a', 'test', 'hello', 'is', 'a', 'word');
        $this->assertEquals(array('hello', 'world', 'this', 'is', 'a', 'test', 'word'), (new Arrays())->fastArrayUnique($array));
	}


    /**
    * @covers util\Arrays::arrayGet()
    */
    public function testArrayGet(){
        $_GET = array();
        $_GET['abc'] = 'def';

        $_GET['nested'] = array( 'key1' => 'val1', 'key2' => 'val2', 'key3' => 'val3' );
        // Looks for $array['abc']
        $this->assertEquals( 'def', (new Arrays())->arrayGet($_GET['abc']) );
        // Looks for $array['nested']['key2']
        $this->assertEquals( 'val2', (new Arrays())->arrayGet($_GET['nested']['key2']) );
        // Looks for $array['doesnotexist']
        $this->assertEquals( 'defaultval', (new Arrays())->arrayGet($_GET['doesnotexist'], 'defaultval') );
    }

    /**
    * @covers util\Arrays::isAssocArray()
    */
    public function testIsAssocArray(){
        $this->assertTrue( (new Arrays())->isAssocArray(array('foo'=>'bar','color'=>'yellow')) );
        $this->assertTrue( (new Arrays())->isAssocArray(array("foo" => array('nested'=>'array'))) );
        $this->assertTrue( (new Arrays())->isAssocArray(array(1 => "baz",'two' => 'bar')) );
        $this->assertFalse( (new Arrays())->isAssocArray(array(1,2,3)) );
        $this->assertFalse( (new Arrays())->isAssocArray("foo") );
        $this->assertFalse( (new Arrays())->isAssocArray(array(array('nested'=>'array'))) );
        $this->assertFalse( (new Arrays())->isAssocArray(array()) );
    }
    /**
    * @covers util\Arrays::isNumericArray()
    */
    public function testIsNumericArray(){
        $this->assertTrue( (new Arrays())->isNumericArray(array()) );
        $this->assertTrue( (new Arrays())->isNumericArray(array(5,6,7)) );
        $this->assertTrue( (new Arrays())->isNumericArray(array(0 => 3, 1 => 3, 2 => 3)) );
        $this->assertTrue( (new Arrays())->isNumericArray(array('foo','bar','baz')) );
        $this->assertFalse( (new Arrays())->isNumericArray('foo') );
        $this->assertFalse( (new Arrays())->isNumericArray(array('foo' => 'bar')) );
        $this->assertFalse( (new Arrays())->isNumericArray(1,2,3) );
        $this->assertFalse( (new Arrays())->isNumericArray(array("foo" => array('nested'=>'array'))) );
        $this->assertFalse( (new Arrays())->isNumericArray(array(0 => 3, 2 => 3, 3 => 3)) );
    }

    public function testArrayFirst(){
        $test = array( 'a' => array( 'a', 'b', 'c' ) );
        $this->assertEquals( 'a', (new Arrays())->arrayFirst( (new Arrays())->arrayGet( $test['a'] ) ) );
    }

    public function testArrayFirstKey(){
        $test = array( 'a' => array( 'a' => 'b', 'c' => 'd' ) );
        $this->assertEquals( 'a', (new Arrays())->arrayFirstKey( (new Arrays())->arrayGet( $test['a'] ) ) );
    }

    public function testArrayLast(){
        $test = array( 'a' => array( 'a', 'b', 'c' ) );
        $this->assertEquals( 'c', (new Arrays())->arrayLast( (new Arrays())->arrayGet( $test['a'] ) ) );
    }

    public function testArrayLastKey(){
        $test = array( 'a' => array( 'a' => 'b', 'c' => 'd' ) );
        $this->assertEquals( 'c', (new Arrays())->arrayLastKey( (new Arrays())->arrayGet( $test['a'] ) ) );
    }

    public function testArrayFlatten(){
        $input = array( 'a', 'b', 'c', 'd', array( 'first' => 'e', 'f', 'second' => 'g', array( 'h', 'third' => 'i', array( array( array( array( 'j', 'k', 'l' ) ) ) ) ) ) );
        $expectNoKeys = range( 'a', 'l' );
        $expectWithKeys = array(
            'a', 'b', 'c', 'd',
            'first' => 'e',
            'f',
            'second' => 'g',
            'h',
            'third' => 'i',
            'j', 'k', 'l'
        );
        $this->assertEquals( $expectWithKeys, (new Arrays())->arrayFlatten( $input ) );
        $this->assertEquals( $expectNoKeys, (new Arrays())->arrayFlatten( $input, false ) );
        $this->assertEquals( $expectWithKeys, (new Arrays())->arrayFlatten( $input, true ) );
    }

    public function testArrayPluck(){
        $array = array(
            array(
                'name' => 'Bob', 'age'  => 37
            ),
            array(
                'name' => 'Fred', 'age'  => 37
            ),
            array(
                'name' => 'Jane', 'age'  => 29
            ),
            array(
                'name' => 'Brandon', 'age'  => 20
            ),
            array(
                'age' => 41
            )
        );
        $obj_array = array(
            'bob' => (object) array(
                'name' => 'Bob', 'age'  => 37
            ),
            'fred' => (object) array(
                'name' => 'Fred', 'age'  => 37
            ),
            'jane' => (object) array(
                'name' => 'Jane', 'age'  => 29
            ),
            'brandon' => (object) array(
                'name' => 'Brandon', 'age'  => 20
            ),
            'invalid' => (object) array(
                'age' => 41
            )
        );
        $obj_array_expect = array(
            'bob'     => 'Bob',
            'fred'    => 'Fred',
            'jane'    => 'Jane',
            'brandon' => 'Brandon'
        );
        $this->assertEquals( array( 'Bob', 'Fred', 'Jane', 'Brandon' ), (new Arrays())->arrayPluck( $array, 'name' ) );
        $this->assertEquals( array( 'Bob', 'Fred', 'Jane', 'Brandon', array( 'age' => 41 ) ), (new Arrays())->arrayPluck( $array, 'name', TRUE, FALSE ) );
        $this->assertEquals( $obj_array_expect, (new Arrays())->arrayPluck( $obj_array, 'name' ) );
        $this->assertEquals( array( 'Bob', 'Fred', 'Jane', 'Brandon' ), (new Arrays())->arrayPluck( $obj_array, 'name', FALSE ) );
        $expected = array('Bob', 'Fred', 'Jane', 'Brandon', 'invalid' => (object)array('age' => 41));
        $this->assertEquals($expected, (new Arrays())->arrayPluck($obj_array, 'name', FALSE, FALSE));
        $expected = array('Bob', 'Fred', 'Jane', 'Brandon', array('age' => 41));
        $this->assertEquals($expected, (new Arrays())->arrayPluck($array, 'name', false, false));
    }


    public function testArraySearchDeep(){
        $users = array(
            1  => (object) array( 'username' => 'brandon', 'age' => 20 ),
            2  => (object) array( 'username' => 'matt', 'age' => 27 ),
            3  => (object) array( 'username' => 'jane', 'age' => 53 ),
            4  => (object) array( 'username' => 'john', 'age' => 41 ),
            5  => (object) array( 'username' => 'steve', 'age' => 11 ),
            6  => (object) array( 'username' => 'fred', 'age' => 42 ),
            7  => (object) array( 'username' => 'rasmus', 'age' => 21 ),
            8  => (object) array( 'username' => 'don', 'age' => 15 ),
            9  => array( 'username' => 'darcy', 'age' => 33 ),
        );
        $test = array(
            1 => 'brandon',
            2 => 'devon',
            3 => array( 'troy' ),
            4 => 'annie'
        );
        $this->assertFalse( (new Arrays())->arraySearchDeep( $test, 'bob' ) );
        $this->assertEquals( 3, (new Arrays())->arraySearchDeep( $test, 'troy' ) );
        $this->assertEquals( 4, (new Arrays())->arraySearchDeep( $test, 'annie' ) );
        $this->assertEquals( 2, (new Arrays())->arraySearchDeep( $test, 'devon', 'devon' ) );
        $this->assertEquals( 7, (new Arrays())->arraySearchDeep( $users, 'rasmus', 'username' ) );
        $this->assertEquals( 9, (new Arrays())->arraySearchDeep( $users, 'darcy', 'username' ) );
        $this->assertEquals( 1, (new Arrays())->arraySearchDeep( $users, 'brandon' ) );
    }

    public function testArrayMapDeep(){
        $input = array(
            '<',
            'abc',
            '>',
            'def',
            array( '&', 'test', '123' ),
            (object) array( 'hey', '<>' )
        );
        $expect = array(
            '&lt;',
            'abc',
            '&gt;',
            'def',
            array( '&amp;', 'test', '123' ),
            (object) array( 'hey', '<>' )
        );
        $this->assertEquals( $expect, (new Arrays())->arrayMapDeep( $input, 'htmlentities' ) );
    }

    public function testArrayMergeDeep(){
        // Simple append
        $dest = array('a','b','c');
        $src = array('d','e','f');
        $result = array('a','b','c','d','e','f');
        $this->assertEquals($result,(new Arrays())->arrayMergeDeep($dest,$src));
        // Nested append
        $dest = array('a','b','2d'=>array('c'));
        $src = array('2d'=>array('d','e','f'));
        $result = array('a','b','2d'=>array('c','d','e','f'));
        $this->assertEquals($result,(new Arrays())->arrayMergeDeep($dest,$src));
        // Nested int key overwrite
        $dest = array(
            'a',
            'b'=>array(
                'c'=>array('d','e'),
                'h'=>0
            )
        );
        $src = array(
            'b'=>array(
                'c'=>array('f','g'),
                'h'=>array('i','j')
            )
        );
        $result = array(
            'a',
            'b'=>array(
                'c'=>array('f','g'),
                'h'=>array('i','j')
            )
        );
        $this->assertEquals($result,(new Arrays())->arrayMergeDeep($dest,$src,false));
    }

    public function testArrayClean(){
        $input = array( 'a', 'b', '', null, false, 0);
        $expect = array('a', 'b');
        $this->assertEquals($expect, (new Arrays())->arrayClean($input));
    }

}