<?php

namespace Fixtures\Tests\Factory;

use Fixtures\Factory\Context;

class ContextTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $user = new \stdClass;
        $factory = function () use ($user) { return $user; };
        $manager = $this->getFactoryManagerMock();
        $manager
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('user'))
            ->will($this->returnValue($factory))
        ;
        $context = new Context($manager);

        $this->assertEquals($user, $context->create('user'), '->create() returns the created fixture');
        $this->assertEquals(array($user), $context->getCreatedFixtures(), '->create() adds the created fixture into the created fixtures list');
    }

    public function testCreateCollection()
    {
        $users = array(
            $foo = new \stdClass,
            $bar = new \stdClass,
            $bar = new \stdClass,
        );
        $usersIterator = new \ArrayIterator($users);
        $factory = function () use ($usersIterator) {
            $user = $usersIterator->current();
            $usersIterator->next();

            return $user;
        };
        $manager = $this->getFactoryManagerMock();
        $manager
            ->expects($this->exactly(3))
            ->method('get')
            ->with($this->equalTo('user'))
            ->will($this->returnValue($factory))
        ;
        $context = new Context($manager);

        $this->assertEquals($users, $context->createCollection(3, 'user'), '->createCollection() returns the created fixtures collection');
        $this->assertEquals($users, $context->getCreatedFixtures(), '->createCollection() adds all the created fixtures to the created fixtures list');
    }

    public function getFactoryManagerMock()
    {
        return $this->getMock('Fixtures\Factory\Manager');
    }
}
