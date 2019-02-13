<?php namespace Database;

function create_connection()
{
    $db = 'database';
    $host = 'db:3306';
    $username = 'admin';
    $password = 'admin';

    $conn = new \PDO("mysql:host=$host;dbname=$db", $username, $password);
    $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    return $conn;
}
