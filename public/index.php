<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Relay\Relay;
use Zend\Diactoros\ServerRequestFactory;

// build container
$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions([
    \ExampleApp\HelloWorld::class => \DI\create(\ExampleApp\HelloWorld::class)
]);
$container = $containerBuilder->build();

// make request and request handler ready
$middlewareQueue = [];

$requestHandler = new Relay($middlewareQueue);
$requestHandler->handle(ServerRequestFactory::fromGlobals());


$helloWorld = $container->get(\ExampleApp\HelloWorld::class);
$helloWorld->announce();