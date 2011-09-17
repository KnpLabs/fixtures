<?php

require_once __DIR__ . '/bootstrap_doctrine.php';

use Entity\User;
use Entity\Article;

$factoryManager = new Fixtures\FactoryManager();
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

$storageManager = new Fixtures\StorageManager();
$storageManager->register(new Fixtures\Storage\Doctrine\ORM($entityManager));

$environment = new Fixtures\Environment($factoryManager, $storageManager);


$environment->reset();
$environment->createCollection(100, 'article', array(
    'author'    => $environment->create('user')
));
