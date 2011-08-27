<?php

namespace Fixtures\Tests;

use Fixtures\ValueProvider;

class ValueProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $manager = $this->getMock('Fixtures\Manager');
        $provider = new ValueProvider($manager, array(
            'username'  => 'THE_USERNAME',
            'email'     => null,
            'is_active' => false
        ));

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
        $user = new \stdClass();
        $manager = $this->getMock('Fixtures\Manager');
        $manager
            ->expects($this->once())
            ->method('create')
            ->with(
                $this->equalTo('THE_USER_FACTORY'),
                $this->equalTo(array(
                    'username'  => 'THE_USERNAME',
                    'email'     => 'THE_EMAIL'
                ))
            )
            ->will($this->returnValue($user))
        ;
        $provider = new ValueProvider($manager, array(
            'title'     => 'THE_TITLE',
            'author'    => array(
                'username'  => 'THE_USERNAME',
                'email'     => 'THE_EMAIL'
            )
        ));
        $this->assertEquals(
            $user, $provider->getRelated('author', 'THE_USER_FACTORY'),
            '->getRelated() uses the factory specified as second argument with the values'
        );

        $user = new \stdClass();
        $manager = $this->getMock('Fixtures\Manager');
        $manager
            ->expects($this->once())
            ->method('create')
            ->with(
                $this->equalTo('THE_USER_FACTORY'),
                $this->equalTo(array())
            )
            ->will($this->returnValue($user))
        ;
        $provider = new ValueProvider($manager, array(
            'title'     => 'THE_TITLE',
            'author'    => 'THE_USER_FACTORY'
        ));
        $this->assertEquals(
            $user, $provider->getRelated('author', 'THE_USER_FACTORY'),
            '->getRelated() uses the value as factory name when it is a string'
        );

        $user = new \stdClass();
        $manager = $this->getMock('Fixtures\Manager');
        $manager
            ->expects($this->once())
            ->method('create')
            ->with(
                $this->equalTo('THE_USER_FACTORY'),
                $this->equalTo(array(
                    'username'  => 'THE_USERNAME',
                    'email'     => 'THE_EMAIL'
                ))
            )
            ->will($this->returnValue($user))
        ;
        $provider = new ValueProvider($manager, array(
            'title'     => 'THE_TITLE',
            'author'    => array(
                '@factory'  => 'THE_USER_FACTORY',
                'username'  => 'THE_USERNAME',
                'email'     => 'THE_EMAIL'
            )
        ));
        $this->assertEquals(
            $user, $provider->getRelated('author', 'THE_USER_FACTORY'),
            '->getRelated() uses the @factory key of the value as factory name'
        );
    }
}
