<?php namespace Database;

/**
 * Create a connection to a database, ensuring any errors throw an exception
 *
 * @param   array $config : Config data for the API containing:
 *              db_host: IP address / name of the host the DB is hosted on
 *              db_name: Name of the database
 *              db_user: Username of the database to enable connection
 *              db_password: Password to enable DB connection
 *
 * @return  \PDO : New database connection
 */
function createConnection($config)
{
    $conn = new \PDO('mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'], $config['db_user'], $config['db_password']);
    $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    return $conn;
}
