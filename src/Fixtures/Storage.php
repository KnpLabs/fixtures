<?php

namespace Fixtures;

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
     * Saves the given fixture
     *
     * @param  object $fixture
     *
     * @return object The saved fixture (mostly the same)
     */
    function save($fixture);

    /**
     * Saves all the given fixtures
     *
     * @param  array An array of fixture instances
     *
     * @return array The saved fixtures array
     */
    function saveAll(array $fixtures);

    /**
     * Resets the storage, clearing all the data it contains
     *
     * @return void
     */
    function reset();
}
