<?php

namespace Fixtures\Storage;

use Fixtures\Storage;

class DoctrineEntityManager implements Storage
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function save($fixture)
    {
        $this->entityManager->persist($fixture);
        $this->entityManager->flush();

        return $fixture;
    }
}
