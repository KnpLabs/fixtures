<?php

namespace Fixtures\Bag;

use Fixtures\Bag;

/**
 * Base for the bag classes
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
abstract class Base implements Bag
{
    /**
     * {@inheritDoc}
     */
    public function addCollection(array $fixtures)
    {
        foreach ($fixtures as $fixture) {
            $this->add($fixture);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function contains($fixture)
    {
        return in_array($fixture, $this->all(), true);
    }

    /**
     * {@inheritDoc}
     */
    public function last()
    {
        $fixtures = $this->all();

        return end($fixtures) ?: null;
    }

    /**
     * {@inheritDoc}
     */
    public function latest($number)
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

        return array_slice($this->all(), -$number);
    }

    /**
     * @see Countable::count()
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->all());
    }
}
