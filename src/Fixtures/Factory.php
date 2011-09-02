<?php

namespace Fixtures;

/**
 * Interface for the factories
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
interface Factory
{
    /**
     * About the factory pattern
     * -------------------------
     *
     * ...
     *
     *
     */

    function create(ValueProvider $values);
}
