<?php

namespace Fixtures\Tests\Sequence;

use Fixtures\Sequence\Text;

class TextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getDataForGetValueTest
     */
    public function testGetValue($text, $index, $expected, $message)
    {
        $text = new Text($text);

        $this->assertEquals($expected, $text->getValue($index), $message);
    }

    public function getDataForGetValueTest()
    {
        return array(
            array(
                'The string with index {index}.',
                123,
                'The string with index 123.',
                '->getValue() replaces \'{index}\' tokens by the index'
            ),
            array(
                'The string number {number}.',
                123,
                'The string number 124.',
                '->getValue() replaces \'{number}\' tokens by the number'
            ),
            array(
                'The string number {number} at index {index}.',
                123,
                'The string number 124 at index 123.',
                '->getValue() replaces all the tokens'
            ),
        );
    }
}
