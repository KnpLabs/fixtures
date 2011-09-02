<?php

use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once __DIR__.'/bootstrap_doctrine.php';

$helperSet = new HelperSet(array(
    'em' => new EntityManagerHelper($entityManager)
));

ConsoleRunner::run($helperSet);
