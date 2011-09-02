<?php

namespace Fixtures;

/**
 * Fixture collection
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Collection implements \ArrayAccess, \IteratorAggregate
{
    private $fixtures;

    /**
     * Constructor
     *
     * @param  array An array of fixture instances
     */
    public function __construct(array $fixtures = array())
    {
        foreach ($fixtures as $fixture) {
            if (!is_object($fixture)) {
                throw new \InvalidArgumentException('All the $fixtures must be objects.');
            }
        }

        $this->fixtures = $fixtures;
    }

    public function merge(Collection $collection)
    {
        foreach ($collection as $key => $fixture) {
            $this->fixtures[$key] = $fixture;
        }
    }

    public function replace($oldFixture, $newFixture)
    {
        $key = array_search($oldFixture, $this->fixtures, true);

        if (false === $key) {
            throw new \InvalidArgumentException('The $oldFixture was not found in the collection.');
        }

        $this->fixtures[$key] = $newFixture;
    }

    /**
     * Returns the underlying array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->fixtures;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->fixtures);
    }

    public function offsetSet($offset, $value)
    {
        if (!is_object($value)) {
            throw new \InvalidArgumentException('The $value must be an object.');
        }

        $this->fixtures[$offset] = $value;
    }

    public function offsetGet($offset)
    {
        return $this->fixtures[$offset];
    }

    public function offsetUnset($offset)
    {
        unset($this->fixtures[$offset]);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->fixtures);
    }
}
