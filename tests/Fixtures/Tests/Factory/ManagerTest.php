<?php

namespace Fixtures\Tests\Factory;

use Fixtures\Factory\Manager;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testSet()
    {
        $manager = new Manager();

        $this->assertAttributeEquals(array(), 'factories', $manager);

        $manager->set('foo', $foo = function () {});
        $manager->set('bar', $bar = function () {});

        $this->assertAttributeEquals(array('foo' => $foo, 'bar' => $bar), 'factories', $manager);
    }

    public function testHas()
    {
        $manager = new Manager();

        $this->assertFalse($manager->has('foo'), '->has() returns FALSE when there is no defined factory');

        $manager->set('foo', function () {});

        $this->assertTrue($manager->has('foo'), '->has() returns TRUE when the specified factory is defined');
        $this->assertFalse($manager->has('bar'), '->has() returns FALSE when the specified factory is NOT defined');
    }

    public function testGet()
    {
        $manager = new Manager();
        $manager->set('foo', $foo = function () {});

        $this->assertEquals($foo, $manager->get('foo'), '->get() returns the specified factory');
    }

    /**
     * @expectedException LogicException
     */
    public function testGetWhenThereIsNoDefinedFactory()
    {
        $manager = new Manager();
        $manager->get('foo');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetAnUndefinedFactory()
    {
        $manager = new Manager();
        $manager->set('foo', function () {});
        $manager->get('bar');
    }

    public function testCreateContext()
    {
        $manager = new Manager();
        $context = $manager->createContext();

        $this->assertInstanceOf('Fixtures\Factory\Context', $context, '->createContext() returns a Context instance');
        $this->assertAttributeEquals($manager, 'manager', $context, '->createContext() creates the Context using itself as manager');
    }
}
