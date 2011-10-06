<?php

namespace Fixtures\Storage;

use Fixtures\Storage;

class Stub implements Storage
{
    private $storage;

    public function __construct()
    {
        $this->purge();
    }

    public function supports($fixture)
    {
        return true;
    }

    public function saveAll(array $fixtures)
    {
        foreach ($fixtures as $fixture) {
            $this->storage->attach($fixture);
        }
    }

    public function purge()
    {
        $this->storage = new \SplObjectStorage;
    }

    public function getSavedFixtures()
    {
        return iterator_to_array($this->storage);
    }

    public function hasSaved($fixture)
    {
        return $this->storage->contains($fixture);
    }
}
