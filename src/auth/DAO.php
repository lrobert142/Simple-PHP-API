<?php

interface DAO
{
    /**
     * Sign up a user with the given data
     *
     * @param   array $data : User data required for signup
     */
    public function signup(array $data);

    /**
     * Login a user using the given data
     *
     * @param   array $data : User data required for login
     */
    public function login(array $data);

    /**
     * Change password for a user
     *
     * @param   array $data : Data required to change a user's password
     */
    public function changePassword(array $data);
}

final class AuthMySqlDAO implements DAO
{
    private $conn;
    const tablename = "Users";

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @inheritdoc
     *
     * @param   array $data : Signup data containing:
     *              email: Email of the user to sign up
     *              password: Plaintext password to encrypt and store
     *
     * @return  int : ID of the inserted user
     *
     * @throws  Exception : If the user's email already exists, or if a DB exception occurs
     */
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

    /**
     * @inheritdoc
     *
     * @param   array $data : Login data containing:
     *              email: Email of the account to log in to
     *              password: Plaintext password to check against the DB
     *
     * @return  array : User information, with sensitive information removed
     *
     * @throws  Exception : If email/password combination are invalid
     */
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

    /**
     * @inheritdoc
     *
     * @param   array $data : Change password data containing:
     *              user_id : ID of the user to change the password for
     *              old_password : Plaintext password to confirm current password against
     *              new_password : Plaintext password to encrypt and replace the existing password
     *
     * @return  bool : True on successful update, false otherwise
     *
     * @throws  Exception : If the supplied old password does not match the stored one
     */
    public function changePassword(array $data)
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
            throw new Exception('Old password invalid', Common\errorCodes()['INVALID_PASSWORD_CHANGE_CREDENTIALS']);
        endif;
    }
}
