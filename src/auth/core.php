<?php

interface Auth
{
    public function signup($data);

    public function login($data);

    public function registerRoutes($router);
}

final class DefaultAuth implements Auth
{
    private $dao;

    public function __construct(DAO $dao)
    {
        return $this->dao = $dao;
    }

    function signup($data)
    {
        return array('id' => $this->dao->signup($data));
    }

    function login($data)
    {
        $user = $this->dao->login($data);
        return array('token' => 'TODO: Generate token from $user!');
    }

    public function registerRoutes($router)
    {
        Common\addRoute($router, 'POST', '/user', array($this, 'signup'), 'AuthSpec\signup');
        Common\addRoute($router, 'POST', '/login', array($this, 'login'), 'AuthSpec\login');
    }
}
