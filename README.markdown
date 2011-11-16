Fixtures
========

A fixtures framework for your PHP 5.3+ applications.

Getting Started
---------------

Let's imagine we are coding a simple blog (oh yeah).

### Define the factories

    <?php

    $factories = new Fixtures\FactoryManager();
    $factories->set('user', function ($provider) {
        $user = new Blog\Model\User();
        $user->setUsername($provider->get('username', 'John'));

        return $user;
    });
    $factories->set('article', function ($provider) {
        $article = new Blog\Model\Article();
        $article->setTitle($provider->get('title', 'The title'));
        $article->setAuthor($provider->getRelation('author', 'user'));
        
        return $article;
    });

### Register the storage

    <?php

    $storages = new Fixtures\StorageManager();
    $storages->register(new Fixtures\Storage\Doctrine\ORM($entityManager));

### Create an environment

    <?php

    $environment = new Fixtures\Environment($factories, $storages);

    $environment->reset(); // it clears all the data from the database

    $article = $environment->create('article');

    echo $article->getTitle(); // prints "The title"
    echo $article->getAuthor()->getUsername(); // prints "John"

    $environment->reset();

    $bob = $environment->create('user', array(
        'username' => 'Bob'
    ));
    $article = $environment->create('article', array(
        'title'     => 'Bob\'s article',
        'author'    => $bob,
    ));

    $environment->reset();

    $articles = $environment->createCollection(100, 'article', array(
        'title'     => new Fixtures\Sequence\Text('Article number {number}'),
        'author'    => $environment->create('user', array('username' => 'Bob')),
    ));
