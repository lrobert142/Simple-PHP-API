<?php namespace Spec;

function signup($params)
{
    $required_fields = array('email', 'password');
    $missing_keys = array_diff($required_fields, array_keys($params));
    $message = '';

    if (!empty($missing_keys)):
        $message .= "Missing keys: " . implode(", ", $missing_keys) . ". ";
    endif;

    if (!empty($params['email']) && !filter_var($params['email'], FILTER_VALIDATE_EMAIL)):
        $message .= "Invalid email format: '" . $params['email'] . "'. ";
    endif;

    return trim($message);
}
