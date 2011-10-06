<?php

namespace Fixtures\Tests\Fixtures;

class User
{
    private $username;

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }
}
