<?php
//This file is the primary entry-point for the API.

use DI\ContainerBuilder;

require_once(__DIR__ . '/../vendor/autoload.php');
require_once('server.php');

$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions(__DIR__ . '/system.php');
$container = $containerBuilder->build();

Server\serve($container);
