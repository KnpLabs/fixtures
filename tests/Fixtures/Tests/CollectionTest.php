<?php

namespace Fixtures\Tests;

use Fixtures\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayAccess()
    {
        $collection = new Collection();
        $this->assertInstanceOf('ArrayAccess', $collection, 'Collection implements the ArrayAccess interface');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testOffsetSetWithANonObjectValue()
    {
        $collection = new Collection();
        $collection->offsetSet('foo', 'bar');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructWithANonObjectValue()
    {
        $collection = new Collection(array('foo' => 'bar'));
    }

    public function testToArray()
    {
        $fixtures = array(new \stdClass(), new \stdClass());
        $collection = new Collection($fixtures);
        $this->assertEquals($fixtures, $collection->toArray(), '->toArray() returns the underlying array');
    }

    /**
     * @dataProvider dataForMergeTest
     */
    public function testMerge($firtsArray, $secondArray, $expectedArray, $message)
    {
        $firstCollection = new Collection($firtsArray);
        $secondCollection = new Collection($secondArray);
        $firstCollection->merge($secondCollection);
        $this->assertEquals($expectedArray, $firstCollection->toArray(), $message);
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

        $collection = new Collection(array('foo' => $foo, 'bar' => $bar));
        $collection->replace($foo, $baz);
        $this->assertEquals(array('foo' => $baz, 'bar' => $bar), $collection->toArray(), '->replace() replaces the given fixture by the other given one');

        $collection = new Collection(array('foo' => $foo));
        $message = '->replace() throws an InvalidArgumentException when the given fixture to replace is not in the collection';
        try {
            $collection->replace($bar, $baz);
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }
    }
}
