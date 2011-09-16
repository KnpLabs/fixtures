<?php

namespace Fixtures\Bag;

use Fixtures\Bag;

/**
 * Simple bag
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Simple extends Base
{
    private $fixtures = array();

    /**
     * Constructor
     *
     * @param  array $fixtures
     */
    public function __construct(array $fixtures = array())
    {
        $this->addCollection($fixtures);
    }

    /**
     * {@inheritDoc}
     */
    public function add($fixture)
    {
        if (!is_object($fixture)) {
            throw new \InvalidArgumentException(sprintf(
                'The $fixture must be an object, %s given.',
                gettype($fixture)
            ));
        }

        if ( ! $this->contains($fixture)) {
            $this->fixtures[] = $fixture;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        return $this->fixtures;
    }

    /**
     * {@inheritDoc}
     */
    public function replace($oldFixture, $newFixture)
    {
        $key = array_search($oldFixture, $this->fixtures, true);

        if (false === $key) {
            throw new \InvalidArgumentException('The $oldFixture was not found in the bag.');
        }

        $this->fixtures[$key] = $newFixture;
    }
}
