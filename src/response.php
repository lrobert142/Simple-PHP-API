<?php namespace Response;

function negotiate_content($data)
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

function handle_with_params($status, $headers, $data)
{
    handle($status, $headers);
    negotiate_content($data);
}

function ok($data)
{
    handle_with_params(200, array(), $data);
}

function not_found()
{
    handle_with_params(404, array(), array('message' => 'URL not found'));
}

function method_not_allowed($allowed_methods)
{
    handle(405, array('Accept: ' . implode(', ', $allowed_methods)));
}
