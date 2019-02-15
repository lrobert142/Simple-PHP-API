<?php

interface Router
{
    public function addRoute($method, $url, $handler, callable $spec = null, $response = 'Response\ok');

    public function dispatch($method, $url, $params);

    public function routes();
}

final class DefaultRouter implements Router
{
    private $routes = array();
    const allow_methods = array('DELETE', 'GET', 'HEAD', 'POST', 'PUT',);

    public function addRoute($method, $url, $handler, callable $spec = null, $response = 'Response\ok')
    {
        if (!in_array(strtoupper($method), $this::allow_methods)):
            throw new Exception('Invalid request method.', Common\errorCodes()['INVALID_REQUEST_METHOD']);
        endif;

        foreach ($this->routes as $route):
            if ($method === $route['method'] && $url === $route['url']):
                throw new Exception('Cannot add duplicate method/route pair', Common\errorCodes()['DUPLICATE_ROUTE']);
            endif;
        endforeach;

        $this->routes[] = array(
            'handler' => $handler,
            'method' => $method,
            'response' => $response,
            'spec' => $spec,
            'url' => $url,
        );
    }

    public function dispatch($method, $url, $params)
    {
        $found_route = null;

        foreach ($this->routes as $route):
            if ($method === $route['method'] && $url === $route['url']):
                $found_route = $route;
                break;
            endif;
        endforeach;

        if (!$found_route):
            throw new Exception('Requested route not found', Common\errorCodes()['ROUTE_NOT_FOUND']);
        endif;

        if ($found_route['spec']):
            $explained = call_user_func($found_route['spec'], $params);
            if (!empty($explained)):
                throw new Exception(implode(' ', $explained), Common\errorCodes()['SPEC_FAILURE']);
            endif;
        endif;

        $found_route['response']($found_route['handler']($params));
    }

    public function routes()
    {
        return $this->routes;
    }
}
