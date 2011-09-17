<?php

namespace Fixtures;

/**
 * Represents a fixtures creation context
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class FactoryContext
{
    private $factoryManager;
    private $createdFixtures = array();

    /**
     * Constructor
     *
     * @param  Manager $factoryManager The factory manager
     */
    public function __construct(FactoryManager $factoryManager)
    {
        $this->factoryManager = $factoryManager;
    }

    /**
     * Returns all the fixtures created in this context
     *
     * @return  array
     */
    public function getCreatedFixtures()
    {
        return $this->createdFixtures;
    }

    /**
     * Creates a fixture
     *
     * @param  string $factory The factory name
     * @param  array  $values  The values for the factory
     *
     * @return object The new fixture
     */
    public function create($factory, array $values = array())
    {
        $values = $this->createValueProvider($values);

        return $this->doCreate($factory, $values);
    }

    /**
     * Creates a collection of fixtures
     *
     * @param  integer $size    The collection size
     * @param  string  $factory The factory name
     * @param  array   $values  The values for the factory
     *
     * @return array The new fixtures collection
     */
    public function createCollection($size, $factory, array $values = array())
    {
        $values = $this->createValueProvider($values);

        $fixtures = array();
        for ($index = 0; $index < $size; $index++) {
            $values->setCurrentIndex($index);
            $fixtures[] = $this->doCreate($factory, $values);
        }

        return $fixtures;
    }

    /**
     * Returns a new value provider for the given values
     *
     * @param  array $values
     *
     * @return Value\Provider
     */
    protected function createValueProvider(array $values)
    {
        return new ValueProvider($values, $this);
    }

    private function doCreate($factory, ValueProvider $values)
    {
        $fixture = $this->factoryManager->get($factory)->create($values);

        $this->createdFixtures[] = $fixture;

        return $fixture;
    }
}
