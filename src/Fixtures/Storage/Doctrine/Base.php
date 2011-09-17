<?php

namespace Fixtures\Storage\Doctrine;

use Fixtures\Storage\Storage;

/**
 * Base class for the Doctrine storages
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
abstract class Base implements Storage
{
    private $manager;

    /**
     * {@inheritDoc}
     */
    public function supports($fixture)
    {
        $factory = $this->manager->getMetadataFactory();
        $factory->getAllMetadata();

        return $factory->hasMetadataFor(get_class($fixture));
    }

    /**
     * {@inheritDoc}
     */
    public function save($fixture)
    {
        $this->manager->persist($fixture);
        $this->manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function saveAll(array $fixtures)
    {
        foreach ($fixtures as $fixture) {
            $this->manager->persist($fixture);
        }

        $this->manager->flush();
    }

    /**
     * Defines the manager
     *
     * @param  ObjectManager $manager
     */
    protected function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * Returns the manager
     *
     * @return ObjectManager
     */
    protected function getManager()
    {
        return $this->manager;
    }

    /**
     * Shortcut method to get all the metadata
     *
     * @return array
     */
    protected function getAllMetadata()
    {
        return $this->manager->getMetadataFactory()->getAllMetadata();
    }
}
