<?php

interface Auth
{
    public function signup($data);
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
        return $this->dao->signup($data);
    }
}
