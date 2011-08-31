<?php

namespace Fixtures\Tests\Storage;

require_once __DIR__.'/../fixtures/User.php';
require_once __DIR__.'/../fixtures/Article.php';

use User, Article;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Fixtures\Storage\DoctrineEntityManager;
use Doctrine\ORM\Mapping\Driver\StaticPHPDriver;

class DoctrineEntityManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testSupports()
    {
        $user = new User();

        $mappingDriver = $this->getMock('Doctrine\ORM\Mapping\Driver\Driver');
        $mappingDriver
            ->expects($this->once())
            ->method('isTransient')
            ->with($this->equalTo('User'))
            ->will($this->returnValue(true))
        ;
        $entityManager = $this->createEntityManager($mappingDriver);
        $storage = new DoctrineEntityManager($entityManager);
        $this->assertTrue($storage->supports($user), '->supports() returns TRUE when the fixture\'s class is transient');


        $mappingDriver = $this->getMock('Doctrine\ORM\Mapping\Driver\Driver');
        $mappingDriver
            ->expects($this->once())
            ->method('isTransient')
            ->with($this->equalTo('User'))
            ->will($this->returnValue(false))
        ;
        $entityManager = $this->createEntityManager($mappingDriver);
        $storage = new DoctrineEntityManager($entityManager);
        $this->assertFalse($storage->supports($user), '->supports() returns FALSE when the fixture\'s class is NOT transient');
    }

    public function testSave()
    {
        $user = new User();

        $entityManager = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->equalTo($user))
        ;
        $entityManager
            ->expects($this->once())
            ->method('flush')
        ;

        $storage = new DoctrineEntityManager($entityManager);

        $this->assertEquals($user, $storage->save($user), '->save() returns the same entity after persisting it and flushing the entity manager');
    }

    public function createEntityManager($mappingDriver)
    {
        $eventManager = new EventManager();
        $connection = $this->getMock('Doctrine\DBAL\Connection', array(), array(), '', false);
        $connection
            ->expects($this->any())
            ->method('getEventManager')
            ->will($this->returnValue($eventManager))
        ;
        $configuration = new Configuration();
        $configuration->setMetadataDriverImpl($mappingDriver);
        $configuration->setProxyDir(__DIR__.'/../fixtures');
        $configuration->setProxyNamespace('Proxy');

        return EntityManager::create($connection, $configuration);
    }
}
