<?php

namespace Fixtures\Bag;

/**
 * Wraps a bag to prohibit the addition of new fixtures so its size is fixed
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Fixed extends Base
{
    private $bag;

    /**
     * Constructor
     *
     * @param  Bag $bag
     */
    public function __construct(Bag $bag)
    {
        $this->bag = $bag;
    }

    /**
     * {@inheritDoc}
     */
    public function add()
    {
        throw new \LogicException('Cannot add any fixture to a fixed bag.');
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        return $this->bag->all();
    }

    /**
     * {@inheritDoc}
     */
    public function replace($oldFixture, $newFixture)
    {
        $this->bag->replace($oldFixture, $newFixture);
    }
}
