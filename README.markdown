Fixtures
========

A fixtures framework for your PHP 5.3+ applications.

Getting Started
---------------

Let's imagine we are coding a simple blog (oh yeah).

### Define the factories

    <?php

    $factories = new Fixtures\Factory\Manager();
    $factories->set('user', function ($provider) {
        $user = new Blog\Model\User();
        $user->setUsername($provider->get('username', 'John'));

        return $user;
    });
    $factories->set('article', function ($provider) {
        $article = new Blog\Model\Article();
        $article->setTitle($provider->get('title', 'The title'));
        $article->setAuthor($provider->getRelation('author', 'user'));
    });

### Register the storage

    <?php

    $storages = new Fixtures\Storage\Manager();
    $storages->register(new Fixtures\Storage\Doctrine\ORM($entityManager));

### Create a manager

    <?php

    $manager = new Fixtures\Manager($factories, $storages);

    $manager->reset(); // it clears all the data from the database

    $article = $manager->create('article');

    echo $article->getTitle() // prints "The title"
    echo $article->getAuthor()->getUsername() // prints "John"

    $manager->reset();

    $bob = $manager->create('user', array(
        'username' => 'Bob'
    ));
    $article = $manager->create('article', array(
        'title'     => 'Bob\'s article',
        'author'    => $bob,
    ));

    $manager->reset();

    $articles = $manager->createCollection(100, 'article', array(
        'title'     => new Fixtures\Value\Sequence\Text('Article number {number}'),
        'author'    => $manager->create('user', array('username' => 'Bob')),
    ));
