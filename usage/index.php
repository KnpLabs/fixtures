<?php

require_once __DIR__ . '/bootstrap_doctrine.php';

use Entity\User;
use Entity\Article;

$factoryManager = new Fixtures\Factory\Manager();
$factoryManager->set('user', function ($provider) {
    $user = new User();
    $user->setUsername($provider->get('username', 'John'));

    return $user;
});
$factoryManager->set('article', function ($provider) {
    $article = new Article();
    $article->setAuthor($provider->getRelation('author', 'user'));
    $article->setTitle($provider->get('title', 'The title'));
    $article->setContent($provider->get('content', 'The content'));

    return $article;
});

$storageManager = new Fixtures\Storage\Manager();
$storageManager->register(new Fixtures\Storage\Doctrine\ORM($entityManager));

$manager = new Fixtures\Manager($factoryManager, $storageManager);


$manager->reset();
$manager->createCollection(100, 'article', array(
    'author'    => $manager->create('user')
));
