<?php

namespace Fixtures\Tests;

use Fixtures\Manager;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $fixture = new \stdClass();
        $savedFixture = new \stdClass();

        $manager = $this->getMock('Fixtures\Manager', array('newInstance', 'saveBag'));
        $manager
            ->expects($this->once())
            ->method('newInstance')
            ->with($this->equalTo('foo'))
            ->will($this->returnValue($fixture))
        ;
        $manager
            ->expects($this->once())
            ->method('saveBag')
            ->will($this->returnCallback(function ($bag) use($savedFixture) {
                $bag->replace($bag->last(), $savedFixture);
            }))
        ;

        $this->assertEquals($savedFixture, $manager->create('foo'), '->create() returns a new saved fixture');
    }

    public function testNewInstance()
    {
        $fixture = new \stdClass();
        $factory = $this->getMock('Fixtures\Factory');
        $factory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($fixture))
        ;
        $manager = new Manager();
        $manager->setFactory('foo', $factory);
        $this->assertEquals($fixture, $manager->newInstance('foo'), '->newInstance() creates a new fixture instance via the specified factory');

        $message = '->newInstance() throws an InvalidArgumentException when the specified factory is not defined';
        try {
            $manager->newInstance('bar');
            $this->fail($message);
        } catch (\InvalidArgumentException $e) {
            $this->anything($message);
        }
    }

    public function testSave()
    {
        $fixture = new \stdClass();
        $savedFixture = new \stdClass();

        $storage1 = $this->getMock('Fixtures\Storage');
        $storage1
            ->expects($this->once())
            ->method('supports')
            ->with($this->equalTo($fixture))
            ->will($this->returnValue(false))
        ;
        $storage1
            ->expects($this->never())
            ->method('save')
        ;
        $storage2 = $this->getMock('Fixtures\Storage');
        $storage2
            ->expects($this->once())
            ->method('supports')
            ->with($this->equalTo($fixture))
            ->will($this->returnValue(true))
        ;
        $storage2
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($fixture))
            ->will($this->returnValue($savedFixture))
        ;
        $storage3 = $this->getMock('Fixtures\Storage');
        $storage3
            ->expects($this->never())
            ->method('supports')
        ;
        $storage3
            ->expects($this->never())
            ->method('save')
        ;
        $manager = new Manager();
        $manager->addStorage($storage1);
        $manager->addStorage($storage2);
        $manager->addStorage($storage3);
        $this->assertEquals($savedFixture, $manager->save($fixture), '->save() uses the first storage supporting the given fixture');

        $manager = new Manager();
        $fixture = new \stdClass();
        $message = '->save() throws a RuntimeException when there is no storage available for the given fixture';
        try {
            $manager->save($fixture);
            $this->fail($message);
        } catch (\RuntimeException $e) {
            $this->anything($message);
        }
    }
}
