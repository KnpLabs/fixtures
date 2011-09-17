<?php

namespace Fixtures;

/**
 * The factory manager handles a set of named factories
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class FactoryManager
{
    private $factories = array();

    /**
     * Defines a factory
     *
     * @param  string          $name
     * @param  Factory|Closure $factory
     */
    public function set($name, $factory)
    {
        if ( ! ($factory instanceof Factory || $factory instanceof \Closure)) {
            throw new \InvalidArgumentException(sprintf(
                'The factory must be either a Factory or a Closure instance, %s given.',
                is_object($factory) ? 'instance of ' . get_class($factory) : gettype($factory)
            ));
        }

        if ($factory instanceof \Closure) {
            $factory = new Factory\Closure($factory);
        }

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
     * @return FactoryContext
     */
    public function createContext()
    {
        return new FactoryContext($this);
    }
}
