<?php

$srcDir    = __DIR__.'/../src';
$vendorDir = $srcDir.'/vendor';

require_once $vendorDir.'/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Fixtures'          => $srcDir,
    'Entity'            => __DIR__,
    'Symfony'           => $vendorDir.'/symfony/src',
    'Doctrine\Common'   => $vendorDir.'/doctrine-common/lib',
    'Doctrine\DBAL'     => $vendorDir.'/doctrine-dbal/lib',
    'Doctrine\ORM'      => $vendorDir.'/doctrine-orm/lib',
));
$loader->register();
