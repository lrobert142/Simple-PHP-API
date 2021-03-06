<?php namespace Server;

use \DI\Container;

require_once('common.php');
require_once('database.php');
require_once('response.php');
require_once('router.php');

require_once('auth/core.php');
require_once('auth/DAO.php');
require_once('auth/spec.php');

/**
 * Serve content to a requester
 *
 * @param   Container $deps : Dependencies required by the system
 */
function serve(Container $deps)
{
    $uri = $_SERVER['REQUEST_URI'];
    if (false !== $pos = strpos($uri, '?')):
        $uri = substr($uri, 0, $pos);
    endif;
    $uri = rawurldecode($uri);

    $params = $_REQUEST;
    $params['authorization'] = $_SERVER['HTTP_AUTHORIZATION'];

    try {
        $router = new \DefaultRouter();
        $deps->get('user.handler')->registerRoutes($router);
        $router->dispatch($_SERVER['REQUEST_METHOD'], $uri, $params);
    } catch (\Exception $e) {
        \Response\badRequest(array('code' => $e->getCode(), 'message' => $e->getMessage()));
    }
}
