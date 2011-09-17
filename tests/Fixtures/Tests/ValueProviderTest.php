<?php

namespace Fixtures\Tests\Value;

use Fixtures\ValueProvider;

class ValueProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getDataToTestGet
     */
    public function testGet(array $values, $name, $default, $expected, $message)
    {
        $context  = $this->getFactoryContextMock();
        $provider = new ValueProvider($values, $context);
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

    public function getFactoryContextMock()
    {
        return $this->getMock('Fixtures\FactoryContext', array(), array(), '', false);
    }
}
