<?php

namespace Fixtures\Tests\Bag;

use Fixtures\Tests\TestCase;
use Fixtures\Bag\Linked;

class LinkedTest extends TestCase
{
    /**
     * @expectedException LogicException
     */
    public function testAdd()
    {
        $bag = new Linked($this->getBagMock(), array($this->createFixture()));
        $bag->add(new \stdClass);
    }

    /**
     * @expectedException LogicException
     */
    public function testAddCollection()
    {
        $bag = new Linked($this->getBagMock(), array($this->createFixture()));
        $bag->addCollection(array());
    }

    public function testIsWithinFixtures()
    {
        $foo = $this->createFixture();
        $bar = $this->createFixture();
        $baz = $this->createFixture();

        $innerBag = $this->getBagMock();
        $innerBag
            ->expects($this->once())
            ->method('contains')
            ->will($this->returnCallback(function ($fixture) use ($foo, $bar) {
                return $foo === $fixture || $bar === $fixture;
            }))
        ;
        $bag = new Linked($innerBag, array($foo, $baz));

        $this->assertTrue($bag->contains($foo), '->contains() returns TRUE when the given fixture is in both links and underlying bag');
        $this->assertFalse($bag->contains($bar), '->contains() returns FALSE when the fixture is NOT in boundaries');
        $this->assertFalse($bag->contains($baz), '->contains() returns FALSE when the fixture is in boundaries but NOT in underlying bag');
    }

    public function getBagMock()
    {
        return $this->getMock('Fixtures\Bag');
    }

    public function createFixture()
    {
        return new \stdClass;
    }
}
