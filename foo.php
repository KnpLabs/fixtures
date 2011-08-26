<?php

require_once __DIR__.'/tests/bootstrap.php.dist';

class User { public $username, $password; }
class Article { public $title, $author, $content; }

$manager = new Fixtures\Manager();

class DummyStorage implements Fixtures\Storage
{
    public $saved;

    public function __construct()
    {
        $this->saved = new \SplObjectStorage();
    }

    public function supports($fixture)
    {
        return true;
    }

    public function save($fixture)
    {
        $this->saved->attach($fixture);

        return $fixture;
    }

    public function reset()
    {
        $this->saved->removeAll();
    }
}

$storage = new DummyStorage();

$manager->addStorage($storage);

$manager->setFactory('user', new Fixtures\Factory\Callback(function ($values) {
    $user = new User();
    $user->username = $values->get('username', 'John');
    $user->password = $values->get('password', 's3cr3t');

    return $user;
}));

$manager->setFactory('article', new Fixtures\Factory\Callback(function ($values) {
    $article = new Article();
    $article->title = $values->get('title', 'The title');
    $article->author = $values->getRelated('author', 'user');
    $article->content = $values->get('content', 'Lorem ipsum...');

    return $article;
}));

$article = $manager->create('article');

var_dump($storage->saved);
