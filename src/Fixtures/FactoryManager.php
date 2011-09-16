<?php

namespace Fixtures;

/**
 * Manages a set of named factories
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class FactoryManager
{
    private $factories = array();

    /**
     * Defines a factory
     *
     * @param  string  $name
     * @param  Factory $factory
     */
    public function set($name, Factory $factory)
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
     * @return Factory
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
}
