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
     *
     * @return object
     */
    public function create($name, array $values = array())
    {
        return $this->save($this->newInstance($name, $values));
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
    public function newInstance($name, array $values = array())
    {
        $valueProvider = new ValueProvider($this, $values);

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

    public function saveAll(array $fixtures)
    {
        // group the fixtures by storage
        $fixturesByStorage = array();
        foreach ($fixtures as $fixture) {
            $storage      = $this->getFixtureStorage($fixture);
            $storageIndex = array_search($storage, $this->storages, true);
            $fixturesByStorage[$storageIndex][] = $fixture;
        }

        // save all the fixtures by storage
        foreach ($fixturesByStorage as $storageIndex => $storageFixtures) {
            $this->storages[$storageIndex]->saveAll($storageFixtures);
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
