<?php

namespace Fixtures\ValueProvider;

use Fixtures\ValueProvider;
use Fixtures\Manager;
use Fixtures\Bag;

class Simple implements ValueProvider
{
    private $manager;
    private $values;
    private $bag;

    /**
     * Constructor
     *
     * @param  Manager $manager
     * @param  array   $values
     * @param  Bag     $bag
     */
    public function __construct(Manager $manager, array $values, Bag $bag)
    {
        $this->manager = $manager;
        $this->values  = $values;
        $this->bag     = $bag;
    }

    /**
     * {@inheritDoc}
     */
    public function get($name, $default = null)
    {
        return $this->has($name) ? $this->values[$name] : $default;
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        return $this->values;
    }

    /**
     * {@inheritDoc}
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

        $fixture = $this->manager->newInstance($factoryName, $value, $this->bag);

        $this->bag[] = $fixture;

        return $fixture;
    }

    /**
     * {@inheritDoc}
     */
    public function has($name)
    {
        return array_key_exists($name, $this->values);
    }
}
