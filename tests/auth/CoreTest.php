<?php declare(strict_types=1);

use ReallySimpleJWT\Token;

require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/../../src/auth/core.php');
require_once(__DIR__ . '/../../src/auth/DAO.php');

use PHPUnit\Framework\TestCase;

final class DefaultAuthSignupTest extends TestCase
{
    public function testSuccess()
    {
        $test_data = array('email' => 'email@domain.com', 'password' => 'Password01');

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

            public function login($_)
            {
                $this->fail("Method should not be called");
            }
        }, array());

        $this->assertEquals(array('id' => 1), $handler->signup($test_data));
    }
}

final class DefaultAuthLoginTest extends TestCase
{
    public function test()
    {
        $test_data = array('email' => 'email@domain.com', 'password' => 'Password01');
        $config = array(
            'jwt_secret' => 'cJ157$5MTPL5',
            'jwt_expiration_epoch' => 60,
            'jwt_issuer' => 'UniTest'
        );

        $handler = new DefaultAuth(new class($test_data) extends TestCase implements DAO
        {
            private $expected;

            public function __construct($data)
            {
                $this->expected = $data;
                parent::__construct();
            }

            public function signup($_)
            {
                $this->fail("Method should not be called");
            }

            public function login($actual)
            {
                $this->assertEquals($this->expected, $actual);
                return array('ID' => 1, 'email' => $actual['email']);
            }
        }, $config);

        $token = $handler->login($test_data)['token'];

        $this->assertTrue(Token::validate($token, $config['jwt_secret']));
        $this->assertEquals(1, Token::getPayload($token, $config['jwt_secret'])['user_id']);
    }
}
