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

            public function resetPassword($_)
            {
                $this->fail("Method should not be called");
            }
        }, array());

        $this->assertEquals(array('id' => 1), $handler->signup($test_data));
    }
}

final class DefaultAuthLoginTest extends TestCase
{
    public function testSuccess()
    {
        $test_data = array('email' => 'email@domain.com', 'password' => 'Password01');
        $config = array(
            'jwt_secret' => 'SuperSecret1!',
            'jwt_expiration_epoch' => 3600,
            'jwt_issuer' => 'UnitTest'
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

            public function resetPassword($_)
            {
                $this->fail("Method should not be called");
            }
        }, $config);

        $token = $handler->login($test_data)['token'];

        $this->assertTrue(Token::validate($token, $config['jwt_secret']));
        $this->assertEquals(1, Token::getPayload($token, $config['jwt_secret'])['user_id']);
    }
}

final class DefaultAuthResetPasswordTest extends TestCase
{
    public function testValidToken()
    {
        $config = array(
            'jwt_secret' => 'SuperSecret1!',
        );
        $test_data = array(
            'old_password' => 'Password01',
            'new_password' => 'Password02',
            'confirm_password' => 'Password02',
            'authorization' => 'Bearer ' . Token::create(1, $config['jwt_secret'], time() + 3600, 'UnitTest')
        );

        $handler = new DefaultAuth(new class($test_data) extends TestCase implements DAO
        {
            private $expected;

            public function __construct($data)
            {
                $data['user_id'] = 1;
                $this->expected = $data;
                parent::__construct();
            }

            public function signup($_)
            {
                $this->fail("Method should not be called");
            }

            public function login($_)
            {
                $this->fail("Method should not be called");
            }

            public function resetPassword($actual)
            {
                $this->assertEquals($this->expected, $actual);
                return true;
            }
        }, $config);

        $this->assertTrue($handler->resetPassword($test_data));
    }

    public function testInvalidToken()
    {
        $config = array(
            'jwt_secret' => 'SuperSecret1!',
        );
        $test_data = array(
            'authorization' => 'Bearer NotAToken'
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

            public function login($_)
            {
                $this->fail("Method should not be called");
            }

            public function resetPassword($_)
            {
                $this->fail("Method should not be called");
            }
        }, $config);

        try {
            $handler->resetPassword($test_data);
        } catch (Exception $e) {
            $this->assertEquals('Authorization token invalid', $e->getMessage());
            $this->assertEquals(Common\errorCodes()['INVALID_AUTHORIZATION_TOKEN'], $e->getCode());
        }
    }
}
