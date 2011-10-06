<?php

namespace Fixtures\Tests;

use Fixtures\FactoryContext;
use Fixtures\FactoryManager;

class FactoryContextTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $user = new \stdClass;
        $factory = $this->getFactoryMock();
        $factory
            ->expects($this->once())
            ->method('create')
            ->with($this->isInstanceOf('Fixtures\ValueProvider'))
            ->will($this->returnValue($user))
        ;
        $manager = new FactoryManager();
        $manager->set('user', $factory);
        $context = new FactoryContext($manager);

        $this->assertEquals($user, $context->create('user'), '->create() returns the created fixture');
        $this->assertEquals(array($user), $context->getCreatedFixtures(), '->create() adds the created fixture into the created fixtures list');
    }

    public function testCreateCollection()
    {
        $users = array(
            $foo = new \stdClass,
            $bar = new \stdClass,
            $baz = new \stdClass,
        );
        $factory = $this->getFactoryMock();
        $factory
            ->expects($this->exactly(3))
            ->method('create')
            ->with($this->isInstanceOf('Fixtures\ValueProvider'))
            ->will($this->onConsecutiveCalls(
                $this->returnValue($foo),
                $this->returnValue($bar),
                $this->returnValue($baz)
            ))
        ;
        $manager = new FactoryManager();
        $manager->set('user', $factory);
        $context = new FactoryContext($manager);

        $this->assertEquals($users, $context->createCollection(3, 'user'), '->createCollection() returns the created fixtures collection');
        $this->assertEquals($users, $context->getCreatedFixtures(), '->createCollection() adds all the created fixtures to the created fixtures list');
    }

    public function getFactoryMock()
    {
        return $this->getMock('Fixtures\Factory');
    }
}
