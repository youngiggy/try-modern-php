<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions([
    \ExampleApp\HelloWorld::class => \DI\create(\ExampleApp\HelloWorld::class)
]);

$container = $containerBuilder->build();

$helloWorld = $container->get(\ExampleApp\HelloWorld::class);
$helloWorld->announce();