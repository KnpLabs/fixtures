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

    /**
     * Merges the given collection into this one
     *
     * @param  Collection $collection
     */
    public function merge(Collection $collection)
    {
        foreach ($collection as $key => $fixture) {
            $this->fixtures[$key] = $fixture;
        }
    }

    /**
     * Replaces the given $oldFixture by the $newFixture
     *
     * @param  object $oldFixture
     * @param  object $newFixture
     */
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

    /**
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->fixtures);
    }

    /**
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        if (!is_object($value)) {
            throw new \InvalidArgumentException('The $value must be an object.');
        }

        $this->fixtures[$offset] = $value;
    }

    /**
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return $this->fixtures[$offset];
    }

    /**
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->fixtures[$offset]);
    }

    /**
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->fixtures);
    }
}
