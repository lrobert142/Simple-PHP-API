<?php namespace Common;

function errorCodes()
{
    //1xx = DB errors
    //2xx = Routing errors
    //3xx = Internal errors
    //4xx = Authentication/Authorization errors
    return array(
        'UNKNOWN_DB_ERROR' => 100,
        'DUPLICATE_FIELD' => 101,
        'INVALID_LOGIN_CREDENTIALS' => 102,
        'INVALID_PASSWORD_RESET_CREDENTIALS' => 103,

        'SPEC_FAILURE' => 200,
        'ROUTE_NOT_FOUND' => 201,

        'DUPLICATE_ROUTE' => 300,
        'INVALID_REQUEST_METHOD' => 301,

        'INVALID_AUTHORIZATION_TOKEN' => 400,
    );
}
