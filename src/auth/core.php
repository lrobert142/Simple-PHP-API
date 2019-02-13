<?php

interface Auth
{
    public function signup($data);

    public function register_routes($router);
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

    public function register_routes($router)
    {
        Common\add_route($router, 'POST', '/user', array($this, 'signup'), 'Spec\signup');
    }
}
