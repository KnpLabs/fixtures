<?php

require_once __DIR__ . '/bootstrap_doctrine.php';

use Entity\User;
use Entity\Article;

$manager = new Fixtures\Manager();
$manager->setFactory('user', new Fixtures\Factory\Callback(function ($values) {
    $user = new User();
    $user->setUsername($values->get('username', 'John'));

    return $user;
}));
$manager->setFactory('article', new Fixtures\Factory\Callback(function ($values) {
    $article = new Article();
    $article->setAuthor($values->getRelated('author', 'user'));
    $article->setTitle($values->get('title', 'The title'));
    $article->setContent($values->get('content', 'The content'));

    return $article;
}));
$manager->addStorage(new Fixtures\Storage\Doctrine\ORM($entityManager));

$manager->reset();
$manager->create('article');
