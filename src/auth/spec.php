<?php namespace AuthSpec;

/**
 * Require that the given keys exist in the provided array
 *
 * @param   array $ks : Keys that must be included
 * @param   array $vs : Values to check for keys in
 *
 * @return  array : Error messages on failed check, empty otherwise
 */
function requireKeys($ks, $vs)
{
    $missing_keys = array_diff($ks, array_keys($vs));
    $messages = array();

    if (!empty($missing_keys)):
        $messages[] = 'Missing keys: ' . implode(", ", $missing_keys);
    endif;

    return $messages;
}

/**
 * Require the given password is at least the specified length
 *
 * @param   string $password : Plaintext password to check length of
 *
 * @return  array : Error messages on failed check, empty otherwise
 */
function passwordLength($password)
{
    $min_password_length = 8;
    $messages = array();

    if (strlen($password) < $min_password_length):
        $messages[] = 'Password too short (min length ' . $min_password_length . ')';
    endif;

    return $messages;
}

/**
 * Require the params for a user signup are all present and valid
 *
 * @param   array $params : Signup params containing:
 *              email: User email
 *              password: User password
 *
 * @return  array : Error messages on failed check, empty otherwise
 */
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

/**
 * Require the params for a user login are all present and valid
 *
 * @param   array $params : Login params containing:
 *              email: Email to login with
 *              password: Password to login with
 *
 * @return  array : Error messages on failed check, empty otherwise
 */
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

/**
 * Require the params for a password change are all present and valid
 *
 * @param   array $params : Password change params containing:
 *              old_password: Old password to be updated
 *              new_password: New password to use
 *              confirm_password: Confirmation of new password
 *              authorization: Auth token
 *
 * @return  array : Error messages on failed check, empty otherwise
 */
function changePassword($params)
{
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
