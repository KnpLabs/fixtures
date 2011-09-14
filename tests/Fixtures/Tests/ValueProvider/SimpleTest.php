<?php

namespace Fixtures\Tests;

use Fixtures\ValueProvider\Simple;

class ValueProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $manager  = $this->getMock('Fixtures\Manager');
        $bag      = $this->getMock('Fixtures\Bag\Simple');
        $provider = new Simple($manager, array(
            'username'  => 'THE_USERNAME',
            'email'     => null,
            'is_active' => false
        ), $bag);

        $this->assertEquals(
            'THE_USERNAME', $provider->get('username'),
            '->get() returns the defined value'
        );
        $this->assertNull(
            $provider->get('email'),
            '->get() returns the defined value even if it is NULL'
        );
        $this->assertNull(
            $provider->get('email', 'THE_DEFAULT_VALUE'),
            '->get() does NOT return the default value even if the value is NULL'
        );
        $this->assertFalse(
            $provider->get('is_active'),
            '->get() returns the defined value even if it is FALSE'
        );
        $this->assertFalse(
            $provider->get('is_active', 'THE_DEFAULT_VALUE'),
            '->get() does NOT return the default value even if the value is FALSE'
        );
        $this->assertNull(
            $provider->get('password'),
            '->get() returns NULL when the specified value is not defined'
        );
        $this->assertEquals(
            'THE_DEFAULT_VALUE', $provider->get('password', 'THE_DEFAULT_VALUE'),
            '->get() returns the default value when the value is not defined'
        );
    }

    public function testGetRelated()
    {
        $related = new \stdClass();
        $manager = $this->getMock('Fixtures\Manager');
        $bag     = $this->getMock('Fixtures\Bag\Simple');
        $manager
            ->expects($this->once())
            ->method('newInstance')
            ->with($this->equalTo('FACTORY'), $this->equalTo(array('bar' => 'Bar', 'baz' => 'Baz')), $this->equalTo($bag))
            ->will($this->returnValue($related));
        ;
        $bag
            ->expects($this->once())
            ->method('add')
            ->with($this->equalTo($related))
        ;
        $provider = new Simple($manager, array('foo' => array('bar' => 'Bar', 'baz' => 'Baz')), $bag);
        $this->assertEquals($related, $provider->getRelated('foo', 'FACTORY'), '->getRelated() creates a new related fixture');
    }
}
