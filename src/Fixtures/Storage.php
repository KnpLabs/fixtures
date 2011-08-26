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
     * Resets the storage, clearing all the data it contains
     *
     * @return void
     */
    function reset();
}
