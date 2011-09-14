<?php

namespace Fixtures\Bag;

/**
 * Storage specific bag
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Storage extends Base
{
    private $source;
    private $storage;

    /**
     * Constructor
     *
     * @param  Bag     $source
     * @param  Storage $storage
     */
    public function __construct(Bag $source, Storage $storage)
    {
        $this->source  = $bag;
        $this->storage = $storage;
    }

    public function add($fixture)
    {
        if ( ! $this->storage->supports($fixture)) {
            throw new \InvalidArgumentException(
                'The fixture must be supported by the storage.'
            );
        }

        $this->source->add($fixture);
    }

    /**
     * {@inheritDoc}
     */
    public function contains($fixture)
    {
        return $this->bag->contains($fixture)
            && $this->storage->supports($fixture);
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        return array_filter($this->bag->all(), array($this, 'contains'));
    }

    /**
     * {@inheritDoc}
     */
    public function replace($oldFixture, $newFixture)
    {
        $key = array_search($oldFixture, $this->all(), true);

        if (false === $key) {
            throw new \InvalidArgumentException(
                'The $oldFixture was not found in the bag.'
            );
        }

        if ( ! $this->storage->supports($fixture)) {
            throw new \InvalidArgumentException(
                'The $newFixture must be supported by the storage.'
            );
        }

        $this->source->replace($oldFixture, $newFixture);
    }
}
