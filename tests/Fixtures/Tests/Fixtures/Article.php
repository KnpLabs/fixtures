<?php

namespace Fixtures\Tests\Fixtures;

class Article
{
    private $title;
    private $author;

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setAuthor(User $author = null)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        return $this->author;
    }
}
