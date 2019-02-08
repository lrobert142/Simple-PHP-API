<?php declare(strict_types=1);
require_once('./src/user/handler.php');

use PHPUnit\Framework\TestCase;

final class UserHandlerTest extends TestCase
{
    public function testCreate()
    {
        $this->assertEquals(array(), User\create());
    }
}
