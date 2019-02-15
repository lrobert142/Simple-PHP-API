<?php declare(strict_types=1);

require_once(__DIR__ . '/../../src/auth/spec.php');

use PHPUnit\Framework\TestCase;

final class AuthRequireKeysTest extends TestCase
{
    public function testAllKeys()
    {
        $this->assertEmpty(AuthSpec\requireKeys(array('foo', 'bar'), array('foo' => '', 'bar' => '')));
    }

    public function testMissingSomeKeys()
    {
        $this->assertEquals(
            array('Missing keys: bar'),
            AuthSpec\requireKeys(array('foo', 'bar'), array('foo' => ''))
        );
    }

    public function testMissingAllKeys()
    {
        $this->assertEquals(
            array('Missing keys: foo, bar'),
            AuthSpec\requireKeys(array('foo', 'bar'), array())
        );
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
        $this->assertEquals(
            array('Missing keys: email, password'),
            AuthSpec\signup(array())
        );
    }

    public function testInvalidEmail()
    {
        $email = 'invalid';
        $this->assertEquals(
            array('Invalid email format: ' . $email),
            AuthSpec\signup(array('email' => $email, 'password' => 'Password01'))
        );
    }

    public function testInvalidPassword()
    {
        $this->assertEquals(
            array('Password too short (min length 8)'),
            AuthSpec\signup(array('email' => 'email@domain.com', 'password' => 'pass'))
        );
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
        $this->assertEquals(
            array('Missing keys: email, password'),
            AuthSpec\login(array())
        );
    }

    public function testInvalidValues()
    {
        $this->assertEquals(
            array('Email empty', 'Password empty'),
            AuthSpec\login(array('email' => '', 'password' => ''))
        );
    }
}

final class AuthSpecResetPasswordTest extends TestCase
{
    public function testValid()
    {
        $this->assertEmpty(AuthSpec\resetPassword(array(
            'old_password' => 'Password01',
            'new_password' => 'Password02',
            'confirm_password' => 'Password02',
            'authorization' => 'Bearer SomeToken'
        )));
    }

    public function testMissingKeys()
    {
        $this->assertEquals(
            array('Missing keys: old_password, new_password, confirm_password, authorization'),
            AuthSpec\resetPassword(array())
        );
    }

    public function testMismatchedPasswords()
    {
        $this->assertEquals(
            array('Passwords do not match'),
            AuthSpec\resetPassword(array(
                'old_password' => 'Password01',
                'new_password' => 'Password02',
                'confirm_password' => 'NotTheSame',
                'authorization' => 'Bearer SomeToken'
            )));
    }

    public function testInvalidNewPassword()
    {
        $this->assertEquals(
            array('Password too short (min length 8)'),
            AuthSpec\resetPassword(array(
                'old_password' => 'Password01',
                'new_password' => 'Bad',
                'confirm_password' => 'Bad',
                'authorization' => 'Bearer SomeToken'
            )));
    }


    public function testInvalidAuthorizationScheme()
    {
        $this->assertEquals(
            array('Invalid authorization scheme'),
            AuthSpec\resetPassword(array(
                'old_password' => 'Password01',
                'new_password' => 'Password02',
                'confirm_password' => 'Password02',
                'authorization' => 'InvalidScheme SomeToken'
            )));
    }

    public function testMissingAuthorizationToken()
    {
        $this->assertEquals(
            array('Authorization token empty'),
            AuthSpec\resetPassword(array(
                'old_password' => 'Password01',
                'new_password' => 'Password02',
                'confirm_password' => 'Password02',
                'authorization' => 'Bearer '
            )));
    }
}
