<?php

namespace Fixtures\Tests\Bag;

use Fixtures\Tests\TestCase;
use Fixtures\Bag\Simple;

class SimpleTest extends TestCase
{
    public function testConstruct()
    {
        $bag = new Simple();
        $this->assertEquals(array(), $bag->all(), '->__construct() creates an empty bag');

        $foo = new \stdClass;
        $bar = new \stdClass;
        $bag = new Simple(array($foo, $bar));
        $this->assertSame(array($foo, $bar), $bag->all(), '->__construct() can take an array of fixtures as first argument');
    }

    public function testAdd()
    {
        $bag = new Simple();
        $bag->add(new \stdClass);
        $this->assertEquals(1, count($bag), '->add() adds a fixture to the bag');
        $bag->add(new \stdClass);
        $this->assertEquals(2, count($bag), '->add() adds a fixture to the bag');

        $fixture = new \stdClass();
        $bag = new Simple();
        $bag->add($fixture);
        $bag->add($fixture);
        $this->assertEquals(1, count($bag), '->add() does not add the same fixture two times');

        foreach (array(array('foo' => 'bat'), 'foo', 123) as $invalidFixture) {
            $this->assertThrowsException(
                'InvalidArgumentException',
                function () use ($bag, $invalidFixture) { $bag->add($invalidFixture); },
                '->add() throws an InvalidArgumentException when the given fixture is not an object'
            );
        }
    }

    public function testAll()
    {
        $bag = new Simple();
        $bag->add($a = new \stdClass());
        $bag->add($b = new \stdClass());

        $this->assertEquals(array($a, $b), $bag->all(), '->all() returns all the fixtures');
    }

    public function testLast()
    {
        $bag = new Simple();
        $this->assertNull($bag->last(), '->last() returns NULL when the bag does not contain any fixture');

        $bag = new Simple();
        $bag->add($a = new \stdClass());
        $bag->add($b = new \stdClass());
        $this->assertEquals($b, $bag->last(), '->last() returns the last added fixture');
    }

    public function testLatest()
    {
        $bag = new Simple();
        $bag->add($a = new \stdClass());
        $bag->add($b = new \stdClass());
        $this->assertEquals(array($b), $bag->latest(1), '->latest() returns an array with the last value when the specified number is 1');
        $this->assertEquals(array($a, $b), $bag->latest(2), '->latest() returns an array with the two latest values');
        $this->assertThrowsException(
            'InvalidArgumentException',
            function () use($bag) { $bag->latest(3); },
            '->latest() throws an InvalidArgumentException when the specified number is more than the available fixtures'
        );
    }

    public function testReplace()
    {
        $a = new \stdClass;
        $b = new \stdClass;
        $c = new \stdClass;

        $bag = new Simple();
        $bag->add($a);
        $bag->add($b);

        $bag->replace($a, $c);

        $this->assertEquals(array($c, $b), $bag->all(), '->replace() replaces the given fixture by the other given one');
    }
}
