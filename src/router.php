<?php

interface Router
{
    /**
     * Add a route the router is able to respond to
     *
     * @param   string $method : HTTP method to respond to
     * @param   string $url : Relative URL to respond to, including leading slash
     * @param   callable $handler : Handler to evoke when handling this request
     * @param   callable|null $spec : Check to perform on the route to ensure params are valid. Optional
     * @param   callable|string $response : HTTP response to invoke on success. Optional
     */
    public function addRoute($method, $url, $handler, $spec = null, $response = 'Response\ok');

    /**
     * Dispatch a request via a HTTP method to a url with params
     *
     * @param   string $method : HTTP method
     * @param   string $url : URL to dispatch to
     * @param   array $params : Params for this request
     */
    public function dispatch($method, $url, $params);

    /**
     * Retrieve a list of registered routes
     */
    public function routes();
}

final class DefaultRouter implements Router
{
    private $routes = array();
    const allow_methods = array('DELETE', 'GET', 'HEAD', 'POST', 'PUT',);

    /**
     * @inheritdoc
     *
     * @throws  Exception : If method is not supported or route already added
     */
    public function addRoute($method, $url, $handler, $spec = null, $response = 'Response\ok')
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

    /**
     * @inheritdoc
     *
     * @throws  Exception : If the route cannot be found or its spec fails (if provided)
     */
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

    /**
     * @inheritdoc
     *
     * @return  array : Registered routes
     */
    public function routes()
    {
        return $this->routes;
    }
}
