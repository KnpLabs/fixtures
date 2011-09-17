<?php

namespace Fixtures\Factory;

use Fixtures\Factory;
use Fixtures\ValueProvider;

/**
 * Closure based factory
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Closure implements Factory
{
    private $closure;

    /**
     * Constructor
     *
     * @param  \Closure $closure
     */
    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * {@inheritDoc}
     */
    public function create(ValueProvider $values)
    {
        return call_user_func_array($this->closure, array($values));
    }
}
