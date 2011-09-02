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
     * @param  string     $name
     * @param  array      $values
     * @param  Collection $collection
     *
     * @return object
     */
    public function create($name, array $values = array(), Collection $collection = null)
    {
        if (null === $collection) {
            $collection = new Collection();
        }

        $collection['main'] = $this->newInstance($name, $values, $collection);

        $this->saveCollection($collection);

        return $collection['main'];
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
     *
     * @return object
     */
    public function newInstance($name, array $values = array(), Collection $collection = null)
    {
        $valueProvider = new ValueProvider($this, $values, $collection);

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
     * Saves the given collection
     *
     * @param  Collection $collection
     */
    public function saveCollection(Collection $collection)
    {
        $fixturesByStorage = array();
        foreach ($collection as $fixture) {
            $storage = $this->getFixtureStorage($fixture);
            $storageIndex = array_search($storage, $this->storages);
            $storages[$storageIndex][] = $fixture;
        }

        foreach ($storages as $storageIndex => $fixtures) {
            $storageCollection = new Collection($fixtures);
            $storage->saveCollection($storageCollection);
            $collection->merge($storageCollection);
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
