<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use ExampleApp\HelloWorld;
use Relay\Relay;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use FastRoute\RouteCollector;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use function FastRoute\simpleDispatcher;

// build container
$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions([
    \ExampleApp\HelloWorld::class =>
        DI\create(\ExampleApp\HelloWorld::class)
            ->constructor(DI\get('Foo'), DI\get('Response')),
    'Foo' => 'bar',
    'Response' => function() {
        return new Response();
    },
]);
$container = $containerBuilder->build();

// make request and request handler ready
$middlewareQueue = [];

$routes = simpleDispatcher(function (RouteCollector $r) {
    $r->get('/hello', HelloWorld::class);
});

$middlewareQueue[] = new FastRoute($routes);
$middlewareQueue[] = new RequestHandler($container);

$requestHandler = new Relay($middlewareQueue);

$response = $requestHandler->handle(ServerRequestFactory::fromGlobals());

$emitter = new SapiEmitter();
$emitter->emit($response);