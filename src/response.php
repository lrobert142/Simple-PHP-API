<?php namespace Response;

/**
 * Handle a HTTP request without body content
 *
 * @param   int $status : HTTP status to respond with
 * @param   array $headers : Additional HTTP headers to respond with
 */
function handle($status, $headers)
{
    http_response_code($status);
    header("Access-Control-Allow-Origin: *");
    foreach ($headers as $header) {
        header($header);
    }
}

/**
 * Handle a HTTP request, responding with body content
 *
 * @param   int $status : HTTP status to respond with
 * @param   array $headers : Additional HTTP headers to respond with
 * @param   * $data : Data to include as body content
 */
function handleWithParams($status, $headers, $data)
{
    handle($status, $headers);
    header('Content-Type: application/json');
    echo json_encode($data);
}

/**
 * Respond success with body content
 *
 * @param   * $data : Data to include as body content
 *
 * @see: https://httpstatuses.com/200
 */
function ok($data)
{
    handleWithParams(200, array(), $data);
}

/**
 * Respond success with no body content
 *
 * @see: https://httpstatuses.com/204
 */
function noContent()
{
    handle(204, array());
}

/**
 * Respond invalid request error with body content
 *
 * @param   * $data : Data to include as body content
 *
 * @see: https://httpstatuses.com/400
 */
function badRequest($data)
{
    handleWithParams(400, array(), $data);
}

/**
 * Respond item not found with no body
 *
 * @see: https://httpstatuses.com/400
 */
function notFound()
{
    handleWithParams(404, array(), array('message' => 'URL not found'));
}

/**
 * Respond HTTP method not allowed with no body, but a list of accepted HTTP methods
 *
 * @param   array $allowed_methods : Allowed HTTP methods for the route
 *
 * @see: https://httpstatuses.com/405
 */
function methodNotAllowed($allowed_methods)
{
    handle(405, array('Accept: ' . implode(', ', $allowed_methods)));
}
