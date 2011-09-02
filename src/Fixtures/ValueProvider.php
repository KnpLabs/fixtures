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
    private $collection;

    /**
     * Constructor
     *
     * @param  Manager    $manager
     * @param  array      $values
     * @param  Collection $collection An optional Collection instance
     */
    public function __construct(Manager $manager, array $values, Collection $collection = null)
    {
        $this->manager    = $manager;
        $this->values     = $values;
        $this->collection = $collection;
    }

    public function get($name, $default = null)
    {
        return $this->has($name) ? $this->values[$name] : $default;
    }

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

        if (null === $this->collection) {
            return $this->manager->create($factoryName, $value);
        } else {
            $fixture = $this->manager->newInstance($factoryName, $value, $this->collection);
            $this->collection[] = $fixture;

            return $fixture;
        }
    }

    public function has($name)
    {
        return array_key_exists($name, $this->values);
    }
}
