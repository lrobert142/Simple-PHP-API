<?php namespace Response;

function negotiateContent($data)
{
    if ($_SERVER['HTTP_ACCEPT'] === 'application/json'):
        header('Content-Type: application/json');
        echo json_encode($data);
    else:
        echo $data;
    endif;
}

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
    negotiateContent($data);
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
