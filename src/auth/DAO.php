<?php

interface DAO
{
    public function signup(array $data);

    public function login(array $data);
}

final class AuthMySqlDAO implements DAO
{
    private $conn;
    const tablename = "Users";

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function signup(array $data)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO ' . $this::tablename . ' (Email, Password) VALUES (:email, :password);');
            $stmt->execute(array(
                ':email' => $data['email'],
                ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
            ));
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            if ($e->getCode() === '23000'):
                throw new Exception('Email address "' . $data['email'] . '" already in use.', Common\errorCodes()['DUPLICATE_FIELD'], $e);
            else:
                throw new Exception('Unknown exception occurred', Common\errorCodes()['UNKNOWN_DB_ERROR'], $e);
            endif;
        }
    }

    public function login(array $data)
    {
        // TODO: Implement login() method.
        return array();
    }
}
