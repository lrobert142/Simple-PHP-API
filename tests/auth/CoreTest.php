<?php declare(strict_types=1);

require_once(__DIR__ . '/../../src/auth/core.php');
require_once(__DIR__ . '/../../src/auth/DAO.php');

use PHPUnit\Framework\TestCase;

final class DefaultAuthSignupTest extends TestCase
{
    public function testSuccess()
    {
        $test_data = array('username' => 'TestUser', 'password' => 'Password01', 'email' => 'email@domain.com');

        $handler = new DefaultAuth(new class($test_data) extends TestCase implements DAO
        {
            private $expected;

            public function __construct($data)
            {
                $this->expected = $data;
                parent::__construct();
            }

            public function signup($actual)
            {
                $this->assertEquals($this->expected, $actual);
                return 1;
            }

            public function login($data) {
                $this->fail("Method should not be called");
            }
        });

        $this->assertEquals(array('id' => 1), $handler->signup($test_data));
    }
}
