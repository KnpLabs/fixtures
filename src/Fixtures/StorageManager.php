<?php

namespace Fixtures;

/**
 * Manages a set of registered storages
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class StorageManager
{
    private $storages = array();

    /**
     * Resets all the registered storages
     *
     * @return void
     */
    public function resetAll()
    {
        foreach ($this->storages as $storage) {
            $storage->purge();
        }
    }

    /**
     * Saves all the given fixtures
     *
     * @param  array $fixtures
     */
    public function saveAll(array $fixtures)
    {
        // group the fixtures by storage
        $storages = new \SplObjectStorage();
        foreach ($fixtures as $fixture) {
            $storage = $this->getFor($fixture);
            if ($storages->contains($storage)) {
                $storages[$storage]->append($fixture);
            } else {
                $storages->attach($storage, new \ArrayObject(array($fixture)));
            }
        }

        // save each storage fixtures
        foreach ($storages as $storage) {
            $storage->saveAll($storages[$storage]->getArrayCopy());
        }
    }

    /**
     * Indicates whether the given storage is already registered
     *
     * @param  Storage $storage
     *
     * @return Boolean
     */
    public function isRegistered(Storage $storage)
    {
        return in_array($storage, $this->storages, true);
    }

    /**
     * Register the given storage
     *
     * @param  Storage $storage
     */
    public function register(Storage $storage)
    {
        if ($this->isRegistered($storage)) {
            throw new \InvalidArgumentException('The given storage is already registered.');
        }

        $this->storages[] = $storage;
    }

    /**
     * Returns the first storage supporting the given fixture
     *
     * @param  object $fixture
     */
    public function getFor($fixture)
    {
        if (empty($this->storages)) {
            throw new \LogicException('Cannot get storage for the given fixture as there is no registered storage.');
        }

        foreach ($this->storages as $storage) {
            if ($storage->supports($fixture)) {
                return $storage;
            }
        }

        throw new \RuntimeException('There is not registered storage supporting the given fixture.');
    }
}
