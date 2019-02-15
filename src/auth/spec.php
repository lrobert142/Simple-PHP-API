<?php namespace AuthSpec;


function requireKeys($ks, $vs)
{
    $missing_keys = array_diff($ks, array_keys($vs));
    $messages = array();

    if (!empty($missing_keys)):
        $messages[] = 'Missing keys: ' . implode(", ", $missing_keys);
    endif;

    return $messages;
}

function passwordLength($password)
{
    $min_password_length = 8;
    $messages = array();

    if (strlen($password) < $min_password_length):
        $messages[] = 'Password too short (min length ' . $min_password_length . ')';
    endif;

    return $messages;
}

function signup($params)
{
    $messages = requireKeys(array('email', 'password'), $params);

    if (isset($params['email']) && !filter_var($params['email'], FILTER_VALIDATE_EMAIL)):
        $messages[] = 'Invalid email format: ' . $params['email'];
    endif;

    if (isset($params['password'])):
        $messages = array_merge($messages, passwordLength($params['password']));
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

function resetPassword($params)
{
    //TODO Verify password length (as in signup, but moved to own fn)
    $messages = requireKeys(array('old_password', 'new_password', 'confirm_password', 'authorization'), $params);

    if (isset($params['new_password']) && isset($params['confirm_password']) && $params['new_password'] !== $params['confirm_password']):
        $messages[] = 'Passwords do not match';
    endif;

    if (isset($params['new_password'])):
        $messages = array_merge($messages, passwordLength($params['new_password']));
    endif;

    if (isset($params['authorization']) && explode(' ', $params['authorization'])[0] !== 'Bearer'):
        $messages[] = 'Invalid authorization scheme';
    endif;

    if (isset($params['authorization']) && empty(explode(' ', $params['authorization'])[1])):
        $messages[] = 'Authorization token empty';
    endif;

    return $messages;
}
