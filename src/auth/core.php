<?php

use ReallySimpleJWT\Token;

interface Auth
{
    public function signup(array $data);

    public function login(array $data);

    public function resetPassword(array $data);

    public function registerRoutes(Router $router);
}

final class DefaultAuth implements Auth
{
    private $dao;
    private $config;

    public function __construct(DAO $dao, array $config)
    {
        $this->dao = $dao;
        $this->config = $config;
    }

    function signup(array $data)
    {
        return array('id' => $this->dao->signup($data));
    }

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

    public function resetPassword(array $data)
    {
        $token = explode(' ', $data['authorization'])[1];
        if (!Token::validate($token, $this->config['jwt_secret'])):
            throw new Exception('Authorization token invalid', Common\errorCodes()['INVALID_AUTHORIZATION_TOKEN']);
        endif;

        $data['user_id'] = Token::getPayload($token, $this->config['jwt_secret'])['user_id'];

        return $this->dao->resetPassword($data);
    }

    public function registerRoutes(Router $router)
    {
        $router->addRoute('POST', '/user', array($this, 'signup'), 'AuthSpec\signup');
        $router->addRoute('POST', '/login', array($this, 'login'), 'AuthSpec\login');
        $router->addRoute('POST', '/reset-password', array($this, 'resetPassword'), 'AuthSpec\resetPassword', 'Response\noContent');
    }
}
