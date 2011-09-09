<?php

namespace Fixtures;

/**
 * Value provider
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class ValueProvider
{
    private $manager;
    private $values;
    private $bag;

    /**
     * Constructor
     *
     * @param  Manager $manager A Manager Instance
     * @param  array   $values  The values
     * @param  Bag     $bag     An optional Bag instance
     */
    public function __construct(Manager $manager, array $values, Bag $bag = null)
    {
        $this->manager = $manager;
        $this->values  = $values;
        $this->bag     = $bag;
    }

    /**
     * Returns the specified value
     *
     * @param  string $name
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        return $this->has($name) ? $this->values[$name] : $default;
    }

    /**
     * Returns the specified relation values
     *
     * @param  string $name
     * @param  string $factoryName
     */
    public function getRelated($name, $factoryName = null)
    {
        $value = $this->get($name, array());

        if (is_object($value)) {
            return $value;
        }

        if (is_string($value)) {
            $value = array('@factory' => $value);
        }

        if (!is_array($value)) {
            throw new \InvalidArgumentException('The value of \'%s\' must be either a string or an array.', $name);
        }

        if (isset($value['@factory'])) {
            $factoryName = $value['@factory'];
            unset($value['@factory']);
        }

        if (null === $factoryName) {
            return null;
        }

        if (null === $this->bag) {
            return $this->manager->create($factoryName, $value);
        } else {
            $fixture = $this->manager->newInstance($factoryName, $value, $this->bag);
            $this->bag[] = $fixture;

            return $fixture;
        }
    }

    /**
     * Indicates whether the specified value exists
     *
     * @param  string $name
     *
     * @return Boolean
     */
    public function has($name)
    {
        return array_key_exists($name, $this->values);
    }
}
