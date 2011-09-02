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
     * Saves the given fixtures collection
     *
     * @param  Collection A fixtures collection
     */
    function saveCollection(Collection $collection);

    /**
     * Resets the storage, clearing all the data it contains
     *
     * @return void
     */
    function reset();
}
