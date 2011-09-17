<?php

namespace Fixtures\Tests;

use Fixtures\FactoryManager;
use Fixtures\Factory\Closure;

class FactoryManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testSet()
    {
        $manager = new FactoryManager();

        $this->assertAttributeEquals(array(), 'factories', $manager);

        $manager->set('foo', $foo = $this->getFactoryMock());
        $manager->set('bar', $bar = $this->getFactoryMock());

        $this->assertAttributeEquals(array('foo' => $foo, 'bar' => $bar), 'factories', $manager);
    }

    public function testSetAClosure()
    {
        $manager = new FactoryManager();
        $manager->set('foo', $foo = function () {});

        $this->assertAttributeEquals(array('foo' => new Closure($foo)), 'factories', $manager, '->set() can be used with a closure');
    }

    public function testHas()
    {
        $manager = new FactoryManager();

        $this->assertFalse($manager->has('foo'), '->has() returns FALSE when there is no defined factory');

        $manager->set('foo', $this->getFactoryMock());

        $this->assertTrue($manager->has('foo'), '->has() returns TRUE when the specified factory is defined');
        $this->assertFalse($manager->has('bar'), '->has() returns FALSE when the specified factory is NOT defined');
    }

    public function testGet()
    {
        $manager = new FactoryManager();
        $manager->set('foo', $foo = $this->getFactoryMock());

        $this->assertEquals($foo, $manager->get('foo'), '->get() returns the specified factory');
    }

    /**
     * @expectedException LogicException
     */
    public function testGetWhenThereIsNoDefinedFactory()
    {
        $manager = new FactoryManager();
        $manager->get('foo');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetAnUndefinedFactory()
    {
        $manager = new FactoryManager();
        $manager->set('foo', $this->getFactoryMock());
        $manager->get('bar');
    }

    public function testCreateContext()
    {
        $manager = new FactoryManager();
        $context = $manager->createContext();

        $this->assertInstanceOf('Fixtures\FactoryContext', $context, '->createContext() returns a Context instance');
        $this->assertAttributeEquals($manager, 'factoryManager', $context, '->createContext() creates the Context using itself as manager');
    }

    public function getFactoryMock()
    {
        return $this->getMock('Fixtures\Factory');
    }
}
