<?php

namespace Fixtures;

/**
 * Interface for the value provider classes
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
interface ValueProvider
{
    /**
     * Indicates whether the specified value exists
     *
     * @param  string $name
     *
     * @return Boolean
     */
    function has($name);

    /**
     * Returns the specified value. If the value does not exist, the default
     * value is returned
     *
     * @param  string $name
     * @param  mixed  $default
     *
     * @return mixed
     */
    function get($name, $default = null);

    /**
     * Returns all the underlying values
     *
     * @return array
     */
    function all();

    /**
     * Returns the specified relation value
     *
     * @param  string $name
     * @param  string $factoryName
     *
     * @return mixed
     */
    function getRelated($name, $factoryName = null);
}
