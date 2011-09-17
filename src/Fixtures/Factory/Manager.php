<?php

namespace Fixtures\Factory;

use Closure;

/**
 * The factory manager handles a set of named factories
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Manager
{
    private $factories = array();

    /**
     * Defines a factory
     *
     * @param  string  $name
     * @param  Closure $factory
     */
    public function set($name, Closure $factory)
    {
        $this->factories[$name] = $factory;
    }

    /**
     * Indicates whether the specified factory is defined
     *
     * @param  string $name
     *
     * @return Boolean
     */
    public function has($name)
    {
        return isset($this->factories[$name]);
    }

    /**
     * Returns the specified factory
     *
     * @param  string $name
     *
     * @return Closure
     */
    public function get($name)
    {
        if (empty($this->factories)) {
            throw new \LogicException('Cannot get any factory as there is no defined factory.');
        }

        if (!$this->has($name)) {
            throw new \InvalidArgumentException(sprintf('The factory \'%s\ is not defined.', $name));
        }

        return $this->factories[$name];
    }

    /**
     * Returns a new factory context
     *
     * @return Context
     */
    public function createContext()
    {
        return new Context($this);
    }
}
