<?php

namespace Fixtures\ValueProvider;

use Fixtures\Sequence;

/**
 * Value provider for collection values
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class Collection extends Simple
{
    private $currentIndex = 0;

    /**
     * Defines the current index
     *
     * @param  integer $index
     */
    public function setCurrentIndex($index)
    {
        $this->currentIndex = intval($index);
    }

    /**
     * Returns the current index
     *
     * @returns integer
     */
    public function getCurrentIndex()
    {
        return $this->currentIndex;
    }

    /**
     * {@inheritDoc}
     */
    public function get($name, $default = null)
    {
        return $this->applySequence(parent::get($name, $default));
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        return array_map(array($this, 'applySequence'), parent::all());
    }

    /**
     * Applies the sequence of the given value if needed
     *
     * @param  mixed $value
     *
     * @return mixed
     */
    private function applySequence($value)
    {
        return $value instanceof Sequence ? $value->getValue($this->currentIndex) : $value;
    }
}
