<?php declare(strict_types=1);

require_once('./src/user/Handler.php');
require_once('./src/user/DAO.php');

use PHPUnit\Framework\TestCase;

final class FakeDAO implements DAO
{

    public function signup($_)
    {
        return 1;
    }
}

final class UserHandlerTest extends TestCase
{

    public function testCreate()
    {
        $handler = new \User\UserHandler(new FakeDAO());
        $this->assertEquals(1, $handler->create(array('username' => 'TestUser', 'password' => 'Password01', 'email' => 'email@domain.com')), "Create user with valid data"));
    }
}
