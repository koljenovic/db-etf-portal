<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/class"), $isDevMode);

$conn = array(
    'url' => 'postgres://portal:test@localhost/portal',
);

$entityManager = EntityManager::create($conn, $config);