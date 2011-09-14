<?php

namespace Fixtures\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Asserts the specified exception will be thrown when executing the given
     * closure
     *
     * @param  string  $class
     * @param  Closure $trigger
     */
    public function assertThrowsException($class, \Closure $trigger, $message = null)
    {
        try {
            $trigger();
            $this->fail($message);
        } catch (\Exception $e) {
            $this->assertInstanceOf($class, $e, $message);
        }
    }
}
