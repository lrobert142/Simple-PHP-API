<?php namespace AuthSpec;

const min_password_length = 8;

function requireKeys($ks, $vs)
{
    $missing_keys = array_diff($ks, array_keys($vs));
    $messages = array();

    if (!empty($missing_keys)):
        $messages[] = 'Missing keys: ' . implode(", ", $missing_keys);
    endif;

    return $messages;
}

function signup($params)
{
    $messages = requireKeys(array('email', 'password'), $params);

    if (isset($params['email']) && !filter_var($params['email'], FILTER_VALIDATE_EMAIL)):
        $messages[] = 'Invalid email format: ' . $params['email'];
    endif;

    if (isset($params['password']) && strlen($params['password']) < min_password_length):
        $messages[] = 'Password too short (min length ' . min_password_length . ')';
    endif;

    return $messages;
}

function login($params)
{
    $messages = requireKeys(array('email', 'password'), $params);

    if (isset($params['email']) && empty(trim($params['email']))):
        $messages[] = 'Email empty';
    endif;

    if (isset($params['password']) && empty(trim($params['password']))):
        $messages[] = 'Password empty';
    endif;

    return $messages;
}
