<?php namespace Common;

function error_codes()
{
    //1xx = DB errors
    //2xx = Routing errors
    return array(
        'UNKNOWN_DB_ERROR' => 100,
        'DUPLICATE_FIELD' => 101,
        'SPEC_FAILURE' => 200,
    );
}

function add_route($router, $method, $route, $handler, callable $spec = null)
{
    if ($spec):
        $explained = call_user_func($spec, $_REQUEST);
        if (!empty($explained)):
            throw new \Exception($explained, \Common\error_codes()['SPEC_FAILURE']);
        endif;
    endif;
    $router->addRoute($method, $route, $handler);
}

