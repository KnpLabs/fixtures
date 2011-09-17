<?php

namespace Fixtures;

/**
 * A sequence is responsible of returning the right value for an item in a
 * fixtures collection
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
interface Sequence
{
    function getValue($index);
}
