<?php

require_once __DIR__.'/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$isDevMode = true;

$configuration = Setup::createAnnotationMetadataConfiguration(array(__DIR__.'/Entity'), $isDevMode);
$connection    = array(
    'driver'    => 'pdo_mysql',
    'host'      => 'localhost',
    'user'      => 'root',
    'dbname'    => 'fixtures',
);

$entityManager = EntityManager::create($connection, $configuration);
