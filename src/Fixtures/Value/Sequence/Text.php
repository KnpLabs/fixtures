<?php

namespace Fixtures\Value\Sequence;

use Fixtures\Value\Sequence;

/**
 * Text sequence
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Text implements Sequence
{
    private $text;

    /**
     * Constructor
     *
     * @param  string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue($index)
    {
        $tokens = array(
            '{index}'   => $index,
            '{number}'  => $index + 1
        );

        return strtr($this->text, $tokens);
    }
}
