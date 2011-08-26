<?php

namespace Fixtures\Factory;

use Fixtures\Factory;

class Callback implements Factory
{
    private $callback;

    /**
     * Constructor
     *
     * @param  Closure|callable $callback
     */
    public function __construct($callback)
    {
        if (!$callback instanceof \Closure || !is_callable($callback)) {
            throw new InvalidArgumentException('The $callback must be either a Closure or a valid callback.');
        }

        $this->callback = $callback;
    }

    /**
     * {@inheritDoc}
     */
    public function create($values)
    {
        return call_user_func($this->callback, $values);
    }
}
