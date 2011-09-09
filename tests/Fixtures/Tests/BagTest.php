<?php

namespace Fixtures\Tests;

use Fixtures\Bag;

class BagTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayAccess()
    {
        $bag = new Bag();
        $this->assertInstanceOf('ArrayAccess', $bag, 'Bag implements the ArrayAccess interface');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testOffsetSetWithANonObjectValue()
    {
        $bag = new Bag();
        $bag->offsetSet('foo', 'bar');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructWithANonObjectValue()
    {
        $bag = new Bag(array('foo' => 'bar'));
    }

    public function testToArray()
    {
        $fixtures = array(new \stdClass(), new \stdClass());
        $bag = new Bag($fixtures);
        $this->assertEquals($fixtures, $bag->toArray(), '->toArray() returns the underlying array');
    }

    /**
     * @dataProvider dataForMergeTest
     */
    public function testMerge($firtsArray, $secondArray, $expectedArray, $message)
    {
        $firstBag = new Bag($firtsArray);
        $secondBag = new Bag($secondArray);
        $firstBag->merge($secondBag);
        $this->assertEquals($expectedArray, $firstBag->toArray(), $message);
    }

    public function dataForMergeTest()
    {
        $foo = new \stdClass();
        $bar = new \stdClass();
        $baz = new \stdClass();
        $bat = new \stdClass();
        $bag = new \stdClass();

        return array(
            array(
                array('foo' => $foo, 'bar' => $bar, 'baz' => $baz),
                array('bar' => $bat, 'baz' => $bag),
                array('foo' => $foo, 'bar' => $bat, 'baz' => $bag),
                '->merge() replaces values of keys'
            ),
            array(
                array(0 => $foo, 1 => $bar, 2 => $bat),
                array(0 => $bat, 2 => $bag),
                array(0 => $bat, 1 => $bar, 2 => $bag),
                '->merge() replaces values of numeric keys'
            ),
            array(
                array('foo' => $foo, 'bar' => $bar),
                array('foo' => $baz, 'bat' => $bat),
                array('foo' => $baz, 'bar' => $bar, 'bat' => $bat),
                '->merge() adds new keys'
            ),
        );
    }

    public function testReplace()
    {
        $foo = new \stdClass();
        $bar = new \stdClass();
        $baz = new \stdClass();

        $bag = new Bag(array('foo' => $foo, 'bar' => $bar));
        $bag->replace($foo, $baz);
        $this->assertEquals(array('foo' => $baz, 'bar' => $bar), $bag->toArray(), '->replace() replaces the given fixture by the other given one');

        $bag = new Bag(array('foo' => $foo));
        $message = '->replace() throws an InvalidArgumentException when the given fixture to replace is not in the bag';
        try {
            $bag->replace($bar, $baz);
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }
    }
}
