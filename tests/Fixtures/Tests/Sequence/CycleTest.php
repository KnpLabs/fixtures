<?php

namespace Fixtures\Tests\Sequence;

use Fixtures\Sequence\Cycle;

class CycleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getDataToTestGetValue
     */
    public function testGetValue(array $elements, $index, $expectedValue, $message)
    {
        $sequence = new Cycle($elements);

        $this->assertEquals($expectedValue, $sequence->getValue($index), $message);
    }

    public function getDataToTestGetValue()
    {
        return array(
            array(
                array('Foo', 'Bar', 'Baz'),
                1,
                'Bar',
                '->getValue() returns the elements matching the specified index'
            ),
            array(
                array('Foo', 'Bar'),
                2,
                'Foo',
                '->getValue() restarts from the begining of the elements when it reaches the end of the list'
            ),
            array(
                array(),
                123,
                null,
                '->getValue() returns NULL when the elements list is empty'
            ),
        );
    }
}
