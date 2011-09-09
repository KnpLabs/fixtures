<?php

namespace Fixtures;

/**
 * The fixtures manager
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Manager
{
    private $factories = array();
    private $storages  = array();

    /**
     * Creates a new fixture and saves it
     *
     * @param  string $name
     * @param  array  $values
     * @param  Bag    $bag
     *
     * @return object
     */
    public function create($name, array $values = array(), Bag $bag = null)
    {
        if (null === $bag) {
            $bag = new Bag();
        }

        $bag['main'] = $this->newInstance($name, $values, $bag);

        $this->saveBag($bag);

        return $bag['main'];
    }

    /**
     * Resets all the storages
     *
     * @return void
     */
    public function reset()
    {
        foreach ($this->storages as $storage) {
            $storage->reset();
        }
    }

    /**
     * Creates a new fixture instance
     *
     * @param  string $name
     * @param  array  $values
     * @param  Bag    $bag
     *
     * @return object
     */
    public function newInstance($name, array $values = array(), Bag $bag = null)
    {
        if (null === $bag) {
            $bag = new Bag();
        }

        $valueProvider = new ValueProvider($this, $values, $bag);

        return $this->getFactory($name)->create($valueProvider);
    }

    /**
     * Saves the given fixture
     *
     * @param  object $fixture
     *
     * @return object The saved fixture (mostly the same)
     */
    public function save($fixture)
    {
        return $this->getFixtureStorage($fixture)->save($fixture);
    }

    /**
     * Saves the given bag
     *
     * @param  Bag $bag
     */
    public function saveBag(Bag $bag)
    {
        $fixturesByStorage = array();
        foreach ($bag as $fixture) {
            $storage = $this->getFixtureStorage($fixture);
            $storageIndex = array_search($storage, $this->storages);
            $storages[$storageIndex][] = $fixture;
        }

        foreach ($storages as $storageIndex => $fixtures) {
            $storageBag = new Bag($fixtures);
            $storage->saveBag($storageBag);
            $bag->merge($storageBag);
        }
    }

    /**
     * Defines a factory
     *
     * @param  string  $name
     * @param  Factory $factory
     */
    public function setFactory($name, Factory $factory)
    {
        $this->factories[$name] = $factory;
    }

    /**
     * Returns the specified factory
     *
     * @param  string $factory
     *
     * @return Factory
     */
    public function getFactory($name)
    {
        if (!isset($this->factories[$name])) {
            throw new \InvalidArgumentException(sprintf('The factory \'%s\' is not defined.', $name));
        }

        return $this->factories[$name];
    }

    /**
     * Adds a storage
     *
     * @param  Storage $storage
     */
    public function addStorage(Storage $storage)
    {
        $this->storages[] = $storage;
    }

    /**
     * Returns the storage adapted to the given fixture
     *
     * @param  object $fixture The fixture instance
     *
     * @return Storage
     *
     * @throws RuntimeException if the storage was not found
     */
    private function getFixtureStorage($fixture)
    {
        foreach ($this->storages as $storage) {
            if ($storage->supports($fixture)) {
                return $storage;
            }
        }

        throw new \RuntimeException(sprintf(
            'There is no storage for the fixture (instance of %s).',
            get_class($fixture)
        ));
    }
}
