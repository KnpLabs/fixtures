<?php

namespace Fixtures\Tests\Storage;

use Fixtures\Storage\Manager;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        $manager = new Manager();

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
        $manager = new Manager();

        $manager->register($storage);
        $manager->register($storage);
    }

    public function testIsRegistered()
    {
        $storage = $this->getStorageMock();
        $manager = new Manager();

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

        $manager = new Manager();
        $manager->register($firstStorage);
        $manager->register($secondStorage);

        $this->assertEquals($secondStorage, $manager->getFor($foo), '->getFixtureStorage() returns the first storage supporting the given fixture');
    }

    /**
     * @expectedException LogicException
     */
    public function testGetForWithNoRegisteredStorage()
    {
        $manager = new Manager();
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

        $manager = new Manager();
        $manager->register($storage);

        $manager->getFor(new \stdClass);
    }

    public function getStorageMock()
    {
        return $this->getMock('Fixtures\Storage\Storage');
    }
}
