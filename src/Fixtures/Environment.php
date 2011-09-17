<?php

namespace Fixtures;

/**
 * The fixtures environment
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Environment
{
    private $factoryManager;
    private $storageManager;

    /**
     * Constructor
     *
     * @param  FactoryManager $factoryManager
     * @param  StorageManager $storageManager
     */
    public function __construct(FactoryManager $factoryManager, StorageManager $storageManager)
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
    public function create($factory, array $values = array())
    {
        $context = $this->factoryManager->createContext();
        $fixture = $context->create($factory, $values);

        $this->storageManager->saveAll($context->getCreatedFixtures());

        return $fixture;
    }

    /**
     * Creates a fixture instance using the specified factory and with the
     * given values
     *
     * @param  string $factory The factory name
     * @param  array  $values  An array of values
     *
     * @return object
     */
    public function instanciate($factory, array $values = array())
    {
        $context = $this->factoryManager->createContext();

        return $context->create($factory, $values);
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
    public function createCollection($size, $factory, array $values = array())
    {
        $context  = $this->factoryManager->createContext();
        $fixtures = $context->createCollection($size, $factory, $values);

        $this->storageManager->saveAll($context->getCreatedFixtures());

        return $fixtures;
    }

    /**
     * Creates a collection of fixture instances using the specified factory
     * and with the given values
     *
     * @param  integer $size    The collection size
     * @param  string  $factory The factory name
     * @param  array   $values  An array of values
     *
     * @return array
     */
    public function instanciateCollection($size, $factory, array $values = array())
    {
        $context  = $this->factoryManager->createContext();

        return $context->createCollection($size, $factory, $values);
    }
}
