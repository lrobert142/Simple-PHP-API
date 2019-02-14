<?php namespace Common;


function errorCodes()
{
    //1xx = DB errors
    //2xx = Routing errors
    //3xx = Internal errors
    return array(
        'UNKNOWN_DB_ERROR' => 100,
        'DUPLICATE_FIELD' => 101,

        'SPEC_FAILURE' => 200,
        'ROUTE_NOT_FOUND' => 201,

        'DUPLICATE_ROUTE' => 300,
        'INVALID_REQUEST_METHOD' => 301,
    );
}
