<?php namespace AuthSpec;

const min_password_length = 8;

function signup($params)
{
    $required_fields = array('email', 'password');
    $missing_keys = array_diff($required_fields, array_keys($params));
    $messages = array();

    if (!empty($missing_keys)):
        $messages[] = "Missing keys: " . implode(", ", $missing_keys);
    endif;

    if (isset($params['email']) && !filter_var($params['email'], FILTER_VALIDATE_EMAIL)):
        $messages[] = "Invalid email format: " . $params['email'];
    endif;

    if (isset($params['password']) && strlen($params['password']) < min_password_length):
        $messages[] = "Password too short (min length " . min_password_length . ")";
    endif;

    return $messages;
}

function login($params)
{
//    $required_fields = array('email', 'password');
    $required_fields = array('foo', 'bar'); //TODO TEMP
    $missing_keys = array_diff($required_fields, array_keys($params));
    $messages = array();

    if (!empty($missing_keys)):
        $messages[] = "Missing keys: " . implode(", ", $missing_keys);
    endif;

    return $messages;
}
