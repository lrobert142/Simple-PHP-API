<?php

use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';
require_once('common.php');
require_once('database.php');
require_once('response.php');

require_once('auth/core.php');
require_once('auth/DAO.php');
require_once('auth/spec.php');

//DI
$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions(__DIR__ . '/system.php');
$container = $containerBuilder->build();

// Strip query string (?foo=bar) and decode URI
$uri = $_SERVER['REQUEST_URI'];
if (false !== $pos = strpos($uri, '?')):
    $uri = substr($uri, 0, $pos);
endif;
$uri = rawurldecode($uri);

try {
    //Routing
    $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) use ($container) {
        $container->get('user.handler')->register_routes($r);
    });
    $routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            Response\not_found();
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            Response\method_not_allowed($routeInfo[1]);
            break;
        case FastRoute\Dispatcher::FOUND:
            $args = $_REQUEST;
            $args['url_args'] = $routeInfo[2];
            Response\ok($routeInfo[1]($args));
            break;
    }
} catch (Exception $e) {
    Response\bad_request(array('code' => $e->getCode(), 'message' => $e->getMessage()));
}
