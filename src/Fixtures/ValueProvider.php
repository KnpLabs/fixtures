<?php

namespace Fixtures;

/**
 * The value provider is passed to the factories to provide them the values and
 * relations
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class ValueProvider
{
    private $values;
    private $context;
    private $currentIndex = 0;

    /**
     * Constructor
     *
     * @param  array          $values  The values
     * @param  FactoryContext $context The factory context used to create the
     *                                 related fixtures
     */
    public function __construct(array $values, FactoryContext $context)
    {
        $this->values  = $values;
        $this->context = $context;
    }

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
     * Indicates whether the specified value is defined
     *
     * @param  string $name
     *
     * @return Boolean
     */
    public function has($name)
    {
        return array_key_exists($name, $this->values);
    }

    /**
     * Returns the specified value
     *
     * @param  string $name
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        $value = $this->has($name) ? $this->values[$name] : $default;

        if ($value instanceof Sequence) {
            $value = $value->getValue($this->currentIndex);
        }

        return $value;
    }

    /**
     * Returns the specified value or raises an exception
     *
     * @param  string $name
     *
     * @return mixed
     *
     * @throw RuntimeException when the value does not exist
     */
    public function getOrError($name)
    {
        if ( ! $this->has($name)) {
            throw new \RuntimeException(sprintf(
                'You must provide a \'%s\' value to the \'%s\' factory.',
                $name,
                $this->context->getCurrentFactoryName()
            ));
        }

        return $this->get($name);
    }

    /**
     * Returns the specified relation
     *
     * @param  string $name
     * @param  string $factory
     *
     * @return object or NULL
     */
    public function getRelation($name, $factory = null)
    {
        $values = $this->get($name, array());

        if (is_object($values)) {
            return $values;
        }

        if (false === $values) {
            return null;
        }

        if (is_scalar($values)) {
            $values = array('@factory' =>  $values);
        }

        if (isset($values['@factory'])) {
            $factory = $values['@factory'];
            unset($values['@factory']);
        }

        if (empty($factory)) {
            return null;
        }

        return $this->context->create($factory, $values);
    }
}
