<?php

namespace Fixtures\Tests\Value;

use Fixtures\Value\Provider;

class ProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getDataToTestGet
     */
    public function testGet(array $values, $name, $default, $expected, $message)
    {
        $context  = $this->getContextMock();
        $provider = new Provider($values, $context);
    }

    public function getDataToTestGet()
    {
        return array(
            array(
                array('foo' => 'Foo'),
                'foo',
                'Default',
                'Foo',
                '->get() returns the specified value when it exists'
            ),
            array(
                array(),
                'foo',
                'Default',
                'Default',
                '->get() returns the default value when the specified value does NOT exist'
            ),
            array(
                array('foo' => null),
                'foo',
                'Default',
                null,
                '->get() return NULL when the specified value is NULL'
            ),
        );
    }

    public function getContextMock()
    {
        return $this->getMock('Fixtures\Factory\Context', array(), array(), '', false);
    }
}
