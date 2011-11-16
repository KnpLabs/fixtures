<?php

namespace Fixtures;

/**
 * Interface for the fixture storage classes
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
interface Storage
{
    /**
     * Indicates whether the storage supports the given fixture
     *
     * @param  object $fixture
     *
     * @return Boolean
     */
    function supports($fixture);

    /**
     * Saves all the given fixtures
     *
     * @param  array An array of fixtures
     *
     * @return void
     */
    function saveAll(array $fixtures);

    /**
     * Purges the storage, clearing all the data it contains
     *
     * @return void
     */
    function reset();
}
