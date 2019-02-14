<?php

interface Auth
{
    public function signup(array $data);

    public function login(array $data);

    public function registerRoutes(Router $router);
}

final class DefaultAuth implements Auth
{
    private $dao;

    public function __construct(DAO $dao)
    {
        return $this->dao = $dao;
    }

    function signup(array $data)
    {
        return array('id' => $this->dao->signup($data));
    }

    function login(array $data)
    {
        $user = $this->dao->login($data);
        return array('token' => 'TODO: Generate token from $user!');
    }

    public function registerRoutes(Router $router)
    {
        $router->addRoute('POST', '/user', array($this, 'signup'), 'AuthSpec\signup');
        $router->addRoute('POST', '/login', array($this, 'login'), 'AuthSpec\login');
    }
}
