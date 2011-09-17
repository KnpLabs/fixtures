<?php

namespace Fixtures;

/**
 * Interface for the factory classes
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
interface Factory
{
    /**
     * Creates a new fixture instance
     *
     * @param  ValueProvider $values
     */
    function create(ValueProvider $values);
}
