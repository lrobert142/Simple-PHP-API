<?php declare(strict_types=1);

require_once(__DIR__ . '/../../src/auth/spec.php');

use PHPUnit\Framework\TestCase;

final class AuthSpecSignupTest extends TestCase
{
    public function testValid()
    {
        $this->assertEmpty(AuthSpec\signup(array('email' => 'email@domain.com', 'password' => 'Password01')));
    }

    public function testMissingKeys()
    {
        $this->assertEquals(array('Missing keys: email, password'), AuthSpec\signup(array()));
    }

    public function testInvalidEmail()
    {
        $email = 'invalid';
        $this->assertEquals(array("Invalid email format: " . $email), AuthSpec\signup(array('email' => $email, 'password' => 'Password01')));
    }

    public function testInvalidPassword()
    {
        $password = 'pass';
        $this->assertEquals(array("Password too short (min length 8)"), AuthSpec\signup(array('email' => 'email@domain.com', 'password' => $password)));
    }
}
