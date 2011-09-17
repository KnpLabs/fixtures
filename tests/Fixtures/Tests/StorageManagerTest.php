<?php

namespace Fixtures\Tests;

use Fixtures\StorageManager;

class StorageManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        $manager = new StorageManager();

        $this->assertAttributeEquals(array(), 'storages', $manager);

        $manager->register($foo = $this->getStorageMock());
        $manager->register($bar = $this->getStorageMock());

        $this->assertAttributeEquals(array($foo, $bar), 'storages', $manager);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRegisterTwiceTheSameStorage()
    {
        $storage = $this->getStorageMock();
        $manager = new StorageManager();

        $manager->register($storage);
        $manager->register($storage);
    }

    public function testIsRegistered()
    {
        $storage = $this->getStorageMock();
        $manager = new StorageManager();

        $this->assertFalse($manager->isRegistered($storage), '->isRegistered() returns FALSE when the given storage is not registered');

        $manager->register($storage);

        $this->assertTrue($manager->isRegistered($storage), '->isRegistered() returns TRUE when the given storage is not registered');
    }

    public function testGetFor()
    {
        $foo = new \stdClass;

        $firstStorage = $this->getStorageMock();
        $firstStorage
            ->expects($this->any())
            ->method('supports')
            ->with($this->equalTo($foo))
            ->will($this->returnValue(false))
        ;
        $secondStorage = $this->getStorageMock();
        $secondStorage
            ->expects($this->any())
            ->method('supports')
            ->with($this->equalTo($foo))
            ->will($this->returnValue(true))
        ;

        $manager = new StorageManager();
        $manager->register($firstStorage);
        $manager->register($secondStorage);

        $this->assertEquals($secondStorage, $manager->getFor($foo), '->getFixtureStorage() returns the first storage supporting the given fixture');
    }

    /**
     * @expectedException LogicException
     */
    public function testGetForWithNoRegisteredStorage()
    {
        $manager = new StorageManager();
        $manager->getFor(new \stdClass);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGetForWithNoSupportingStorage()
    {
        $storage = $this->getStorageMock();
        $storage
            ->expects($this->any())
            ->method('supports')
            ->will($this->returnValue(false))
        ;

        $manager = new StorageManager();
        $manager->register($storage);

        $manager->getFor(new \stdClass);
    }

    public function getStorageMock()
    {
        return $this->getMock('Fixtures\Storage');
    }
}
