<?php

namespace Fixtures\Tests;

use Fixtures\Environment;
use Fixtures\FactoryContext;

class EnvironmentTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $user = new \stdClass;
        $factoryManager = $this->getFactoryManagerMock();
        $factoryManager
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('user'))
            ->will($this->returnValue(function () use ($user) {
                return $user;
            }))
        ;
        $factoryManager
            ->expects($this->once())
            ->method('createContext')
            ->will($this->returnValue(new FactoryContext($factoryManager)))
        ;
        $storageManager = $this->getStorageManagerMock();
        $storageManager
            ->expects($this->once())
            ->method('saveAll')
            ->with($this->equalTo(array($user)))
        ;

        $manager = new Environment($factoryManager, $storageManager);

        $this->assertEquals($user, $manager->create('user', array('username' => 'John')));
    }

    public function testCreateCollection()
    {
        $users = array(
            $foo = new \stdClass,
            $bar = new \stdClass,
            $baz = new \stdClass,
            $bat = new \stdClass,
            $ban = new \stdClass,
        );
        $usersIterator = new \ArrayIterator($users);
        $factoryManager = $this->getFactoryManagerMock();
        $factoryManager
            ->expects($this->exactly(5))
            ->method('get')
            ->with($this->equalTo('user'))
            ->will($this->returnValue(function () use ($usersIterator) {
                $user = $usersIterator->current();
                $usersIterator->next();

                return $user;
            }))
        ;
        $factoryManager
            ->expects($this->once())
            ->method('createContext')
            ->will($this->returnValue(new FactoryContext($factoryManager)))
        ;
        $storageManager = $this->getStorageManagerMock();
        $storageManager
            ->expects($this->once())
            ->method('saveAll')
            ->with($this->equalTo($users))
        ;

        $manager = new Environment($factoryManager, $storageManager);

        $this->assertEquals($users, $manager->createCollection(5, 'user', array('username' => 'John')));
    }

    public function getFactoryManagerMock()
    {
        return $this->getMock('Fixtures\FactoryManager');
    }

    public function getStorageManagerMock()
    {
        return $this->getMock('Fixtures\StorageManager');
    }
}
