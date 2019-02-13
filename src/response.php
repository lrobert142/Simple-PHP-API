<?php namespace Response;

function handle($status, $headers)
{
    http_response_code($status);
    header("Access-Control-Allow-Origin: *");
    foreach ($headers as $header) {
        header($header);
    }
}

function handleWithParams($status, $headers, $data)
{
    handle($status, $headers);
    header('Content-Type: application/json');
    echo json_encode($data);
}

function ok($data)
{
    handleWithParams(200, array(), $data);
}

function badRequest($data)
{
    handleWithParams(400, array(), $data);
}

function notFound()
{
    handleWithParams(404, array(), array('message' => 'URL not found'));
}

function methodNotAllowed($allowed_methods)
{
    handle(405, array('Accept: ' . implode(', ', $allowed_methods)));
}
