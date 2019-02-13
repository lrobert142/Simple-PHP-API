<?php namespace Common;

function errorCodes()
{
    //1xx = DB errors
    //2xx = Routing errors
    return array(
        'UNKNOWN_DB_ERROR' => 100,
        'DUPLICATE_FIELD' => 101,
        'SPEC_FAILURE' => 200,
    );
}

function addRoute($router, $method, $route, $handler, callable $spec = null)
{
    if ($spec):
        $explained = call_user_func($spec, $_REQUEST);
        if (!empty($explained)):
            throw new \Exception(implode(' ', $explained), \Common\errorCodes()['SPEC_FAILURE']);
        endif;
    endif;
    $router->addRoute($method, $route, $handler);
}

