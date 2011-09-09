<?php

namespace Fixtures;

/**
 * Fixtures bag
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Bag implements \Countable, \IteratorAggregate
{
    private $fixtures = array();

    /**
     * Constructor
     *
     * @param  array $fixtures
     */
    public function __construct(array $fixtures = array())
    {
        foreach ($fixtures as $fixture) {
            $this->add($fixture);
        }
    }

    /**
     * Adds the given fixture
     *
     * @param  object $fixture
     */
    public function add($fixture)
    {
        if (!is_object($fixture)) {
            throw new \InvalidArgumentException(sprintf(
                'The $fixture must be an object, %s given.',
                gettype($fixture)
            ));
        }

        $this->fixtures[] = $fixture;
    }

    /**
     * Adds the given fixtures
     *
     * @param  array $fixtures
     */
    public function addCollection(array $fixtures)
    {
        foreach ($fixtures as $fixture) {
            $this->push($fixture);
        }
    }

    /**
     * Returns the last fixture
     *
     * @return object
     */
    public function getLast()
    {
        return end($this->fixtures);
    }

    /**
     * Returns the $number latest fixtures
     *
     * @return array
     */
    public function getLatest($number)
    {
        if (!is_int($number)) {
            throw new \InvalidArgumentException(sprintf(
                'The $number must be an integer, %s given',
                gettype($number)
            ));
        } elseif ($number < 1) {
            throw new \InvalidArgumentException(sprintf(
                'The $number must be greater than or equal to one, %d given.',
                $number
            ));
        } elseif ($number > $this->count()) {
            throw new \InvalidArgumentException(sprintf(
                'You requested the %d latest but the bag contains only %d fixture(s).',
                $number, $this->count()
            ));
        }

        return array_slice($this->fixtures, -$number);
    }

    /**
     * Merges the given bag into this one
     *
     * @param  Bag $bag
     */
    public function merge(Bag $bag)
    {
        foreach ($bag as $key => $fixture) {
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
            throw new \InvalidArgumentException('The $oldFixture was not found in the bag.');
        }

        $this->fixtures[$key] = $newFixture;
    }

    /**
     * @see Countable::count()
     */
    public function count()
    {
        return count($this->fixtures);
    }

    /**
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->fixtures);
    }
}
