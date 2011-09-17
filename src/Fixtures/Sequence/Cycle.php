<?php

namespace Fixtures\Sequence;

use Fixtures\Sequence;

/**
 * Sequence cycling over the elements list
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Cycle implements Sequence
{
    private $elements;

    /**
     * Constructor
     *
     * @param  array $elements
     */
    public function __construct(array $elements)
    {
        $this->elements = array_values($elements);
    }

    /**
     * {@inheritDoc}
     */
    public function getValue($index)
    {
        if (empty($this->elements)) {
            return null;
        }

        $index = $index % (count($this->elements) - 1);

        return $this->elements[$index];
    }
}
