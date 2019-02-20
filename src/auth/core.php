<?php

use ReallySimpleJWT\Token;

/**
 * Functionality to allow users authorization steps
 */
interface Auth
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

    /**
     * Register REST routes with the router
     *
     * @param   Router $router : The router to register routes with
     */
    public function registerRoutes(Router $router);
}

/**
 * Default authorization implementation, allowing users to manipulate only their data
 */
final class DefaultAuth implements Auth
{
    private $dao;
    private $config;

    public function __construct(DAO $dao, array $config)
    {
        $this->dao = $dao;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     *
     * @return  array: New user ID
     */
    function signup(array $data)
    {
        return array('id' => $this->dao->signup($data));
    }

    /**
     * @inheritdoc
     *
     * @return  array : Unique token used to identify the logged in user
     */
    function login(array $data)
    {
        $user = $this->dao->login($data);
        return array('token' => Token::create(
            $user['ID'],
            $this->config['jwt_secret'],
            time() + $this->config['jwt_expiration_epoch'],
            $this->config['jwt_issuer']
        ));
    }

    /**
     * @inheritdoc
     *
     * @param   array $data : Change password data containing:
     *              authorization: Auth token
     *
     * @return  bool : Whether or not the reset request succeeded
     *
     * @throws  Exception : If token is invalid or expired
     */
    public function changePassword(array $data)
    {
        $token = explode(' ', $data['authorization'])[1];
        if (!Token::validate($token, $this->config['jwt_secret'])):
            throw new Exception('Authorization token invalid', Common\errorCodes()['INVALID_AUTHORIZATION_TOKEN']);
        endif;

        $data['user_id'] = Token::getPayload($token, $this->config['jwt_secret'])['user_id'];

        return $this->dao->changePassword($data);
    }

    /**
     * @inheritdoc
     */
    public function registerRoutes(Router $router)
    {
        $router->addRoute('POST', '/user', array($this, 'signup'), 'AuthSpec\signup');
        $router->addRoute('POST', '/login', array($this, 'login'), 'AuthSpec\login');
        $router->addRoute('POST', '/change-password', array($this, 'changePassword'), 'AuthSpec\changePassword', 'Response\noContent');
    }
}
