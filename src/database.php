<?php namespace Database;

function createConnection()
{
    $db = 'database';
    $host = 'db:3306';
    $username = 'admin';
    $password = 'admin';

    $conn = new \PDO("mysql:host=$host;dbname=$db", $username, $password);
    $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    return $conn;
}
