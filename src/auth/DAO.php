<?php

interface DAO
{
    public function signup(array $data);

    public function login(array $data);

    public function resetPassword(array $data);
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
        $pass_stmt = $this->conn->prepare('SELECT Password FROM Users WHERE Email = :email LIMIT 1');
        $pass_stmt->execute(array(
            ':email' => $data['email'],
        ));
        $result = $pass_stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($result['Password']) && password_verify($data['password'], $result['Password'])):
            $user_stmt = $this->conn->prepare('SELECT ID, Email FROM Users WHERE Email = :email LIMIT 1');
            $user_stmt->execute(array(
                ':email' => $data['email'],
            ));
            return $user_stmt->fetch(PDO::FETCH_ASSOC);
        else:
            throw new Exception('Invalid login credentials', Common\errorCodes()['INVALID_LOGIN_CREDENTIALS']);
        endif;
    }

    public function resetPassword(array $data)
    {
        $pass_stmt = $this->conn->prepare('SELECT Password FROM Users WHERE ID = :id LIMIT 1');
        $pass_stmt->execute(array(
            ':id' => $data['user_id'],
        ));
        $result = $pass_stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($result['Password']) && password_verify($data['old_password'], $result['Password'])):
            $stmt = $this->conn->prepare('UPDATE ' . $this::tablename . ' SET password = :password WHERE ID = :id');
            return $stmt->execute(array(
                ':password' => password_hash($data['new_password'], PASSWORD_BCRYPT),
                ':id' => $data['user_id'],
            ));
        else:
            throw new Exception('Old password invalid', Common\errorCodes()['INVALID_PASSWORD_RESET_CREDENTIALS']);
        endif;
    }
}
