<?php

interface DAO
{
    public function signup($data);
}

final class AuthMySqlDAO implements DAO
{
    private $conn;
    const tablename = "Users";

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function signup($data)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO ' . $this::tablename . ' (Email, Password) VALUES (:email, :password);');
            return $stmt->execute(array(
                ':email' => $data['email'],
                ':password' => $data['password'],
            ));
        } catch (PDOException $e) {
            //TODO Cast + rethrow error to something we can handle better
            var_dump($e);
            return -1;
        }
    }
}
