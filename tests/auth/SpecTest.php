<?php declare(strict_types=1);

require_once(__DIR__ . '/../../src/auth/spec.php');

use PHPUnit\Framework\TestCase;

final class AuthRequireKeysTest extends TestCase
{
    public function testAllKeys()
    {
        $this->assertEmpty(AuthSpec\requireKeys(array('foo', 'bar'), array('foo' => '', 'bar' => '')));
    }

    public function testMissingKeys()
    {
        $this->assertEquals(array('Missing keys: foo, bar'), AuthSpec\requireKeys(array('foo', 'bar'), array()));
    }
}

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
        $this->assertEquals(array('Invalid email format: ' . $email), AuthSpec\signup(array('email' => $email, 'password' => 'Password01')));
    }

    public function testInvalidPassword()
    {
        $password = 'pass';
        $this->assertEquals(array('Password too short (min length 8)'), AuthSpec\signup(array('email' => 'email@domain.com', 'password' => $password)));
    }
}

final class AuthSpecLoginTest extends TestCase
{
    public function testValid()
    {
        $this->assertEmpty(AuthSpec\login(array('email' => 'email@domain.com', 'password' => 'Password01')));
    }

    public function testMissingKeys()
    {
        $this->assertEquals(array('Missing keys: email, password'), AuthSpec\login(array()));
    }

    public function testInvalidValues()
    {
        $this->assertEquals(array('Email empty', 'Password empty'), AuthSpec\login(array('email' => '', 'password' => '')));
    }
}
