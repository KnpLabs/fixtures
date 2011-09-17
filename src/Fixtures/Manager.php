<?php

namespace Fixtures;

/**
 * The fixtures manager
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Manager
{
    private $factoryManager;
    private $storageManager;

    /**
     * Constructor
     *
     * @param  Factory\Manager $factoryManager
     * @param  Storage\Manager $storageManager
     */
    public function __construct(Factory\Manager $factoryManager, Storage\Manager $storageManager)
    {
        $this->factoryManager = $factoryManager;
        $this->storageManager = $storageManager;
    }

    /**
     * Resets all the storages
     */
    public function reset()
    {
        $this->storageManager->resetAll();
    }

    /**
     * Creates a fixture using the specified factory and with the given values
     *
     * @param  string $factory The factory name
     * @param  array  $values  An array of values
     *
     * @return object
     */
    public function create($factory, array $values)
    {
        $context = $this->factoryManager->createContext();
        $fixture = $context->create($factory, $values);

        $this->storageManager->saveAll($context->getCreatedFixtures());

        return $fixture;
    }

    /**
     * Creates a collection of fixtures using the specified factory and the
     * given values
     *
     * @param  integer $size    The collection size
     * @param  string  $factory The factory name
     * @param  array   $values  An array of values
     *
     * @return array
     */
    public function createCollection($size, $factory, array $values)
    {
        $context  = $this->factoryManager->createContext();
        $fixtures = $context->createCollection($size, $factory, $values);

        $this->storageManager->saveAll($context->getCreatedFixtures());

        return $fixtures;
    }
}
