<?php

namespace Fixtures\Tests;

use Fixtures\Environment;
use Fixtures\FactoryManager;
use Fixtures\Storage;
use Fixtures\StorageManager;

class EnvironmentTest extends \PHPUnit_Framework_TestCase
{
    private $environment;
    private $storage;

    public function setUp()
    {
        $this->storage = new Storage\Stub;

        $storageManager = new StorageManager;
        $storageManager->register($this->storage);

        $factoryManager = new FactoryManager;
        $factoryManager->set('user', function ($values) {
            $user = new Fixtures\User();
            $user->setUsername($values->get('username', 'John'));

            return $user;
        });
        $factoryManager->set('article', function ($values) {
            $article = new Fixtures\Article();
            $article->setTitle($values->get('title', 'The article'));
            $article->setAuthor($values->getRelation('author', 'user'));

            return $article;
        });

        $this->environment = new Environment($factoryManager, $storageManager);
    }

    public function testCreateHavingNoRelationWithoutValues()
    {
        $user = $this->environment->create('user');
        $this->assertInstanceOf('Fixtures\Tests\Fixtures\User', $user);
        $this->assertTrue($this->storage->hasSaved($user));
        $this->assertEquals('John', $user->getUsername());
    }

    public function testCreateHavingNoRelationWithValues()
    {
        $user = $this->environment->create('user', array('username' => 'Herzult'));
        $this->assertInstanceOf('Fixtures\Tests\Fixtures\User', $user);
        $this->assertTrue($this->storage->hasSaved($user));
        $this->assertEquals('Herzult', $user->getUsername());
    }

    public function testCreateHavingManyToOneRelationWithoutValues()
    {
        $article = $this->environment->create('article');
        $this->assertInstanceOf('Fixtures\Tests\Fixtures\Article', $article);
        $this->assertEquals('The article', $article->getTitle());
        $savedFixtures = $this->storage->getSavedFixtures();
        $this->assertEquals(2, count($savedFixtures));
        $this->assertEquals($article, $savedFixtures[1]);
        $this->assertInstanceOf('Fixtures\Tests\Fixtures\User', $savedFixtures[0]);
        $this->assertEquals('John', $savedFixtures[0]->getUsername());
    }

    public function testCreateHavingManyToOneRelationWithValues()
    {
        $article = $this->environment->create('article', array(
            'title'  => 'Some title',
            'author' => array(
                'username'  => 'Herzult'
            )
        ));
        $this->assertInstanceOf('Fixtures\Tests\Fixtures\Article', $article);
        $this->assertEquals('Some title', $article->getTitle());
        $savedFixtures = $this->storage->getSavedFixtures();
        $this->assertEquals(2, count($savedFixtures));
        $this->assertEquals($article, $savedFixtures[1]);
        $this->assertInstanceOf('Fixtures\Tests\Fixtures\User', $savedFixtures[0]);
        $this->assertEquals('Herzult', $savedFixtures[0]->getUsername());
    }
}
