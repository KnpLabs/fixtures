<?php

namespace Fixtures;

/**
 * Interface for the fixture collection classes
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
interface Bag extends \Countable, \IteratorAggregate
{
    /**
     * Adds the given fixture
     *
     * @param  object $fixture
     */
    function add($fixture);

    /**
     * Adds all the given fixtures
     *
     * @param  array $fixtures
     */
    function addCollection(array $fixtures);

    /**
     * Indicates whether the specified fixture is in the bag
     *
     * @param  object $fixture
     */
    function contains($fixture);

    /**
     * Returns all the fixtures
     *
     * @return array
     */
    function all();

    /**
     * Returns the last fixture
     *
     * @return object
     */
    function last();

    /**
     * Returns the $number latest fixtures
     *
     * @param  integer $number
     *
     * @return array
     */
    function latest($number);

    /**
     * Replaces the given $oldFixture by the $newFixture
     *
     * @param  object $oldFixture
     * @param  object $newFixture
     */
    function replace($oldFixture, $newFixture);
}
