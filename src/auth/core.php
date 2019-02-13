<?php

interface Auth
{
    public function signup($data);

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

    public function registerRoutes($router)
    {
        Common\addRoute($router, 'POST', '/user', array($this, 'signup'), 'Spec\signup');
    }
}
