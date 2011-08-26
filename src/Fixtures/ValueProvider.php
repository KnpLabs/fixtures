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

    /**
     * Constructor
     *
     * @param  Manager $manager
     * @param  array   $values
     */
    public function __construct(Manager $manager, array $values)
    {
        $this->manager = $manager;
        $this->values  = $values;
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

        return $this->manager->create($factoryName, $value);
    }

    public function has($name)
    {
        return array_key_exists($name, $this->values);
    }
}
