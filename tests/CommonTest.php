<?php declare(strict_types=1);

require_once(__DIR__ . '/../src/common.php');

use PHPUnit\Framework\TestCase;

final class CommonAddRouteTest extends TestCase
{
    private $fakeRouter;

    public function __construct()
    {
        $this->fakeRouter = new class
        {
            private $routes = array();

            public function addRoute($method, $route, $handler)
            {
                $this->routes[] = array($method, $route, $handler);
            }

            public function routes()
            {
                return $this->routes;
            }
        };
        parent::__construct();
    }

    public function testSuccessNoSpec()
    {
        $method = 'TEST';
        $url = '/unit';
        $handler = 'test-handler';
        Common\addRoute($this->fakeRouter, $method, $url, $handler);
        $this->assertEquals(array(array($method, $url, $handler)), $this->fakeRouter->routes());
    }

    public function testSuccessWithSpec()
    {
        $_REQUEST = array('foo' => 'bar', 'baz' => 'quux');

        $method = 'TEST';
        $url = '/unit';
        $handler = 'test-handler';
        Common\addRoute($this->fakeRouter, $method, $url, $handler, function ($params) {
            $this->assertEquals($_REQUEST, $params);
            return array();
        });
        $this->assertEquals(array(array($method, $url, $handler)), $this->fakeRouter->routes());
    }

    public function testFailSpec()
    {
        $method = 'TEST';
        $url = '/unit';
        $handler = 'test-handler';
        try {
            Common\addRoute($this->fakeRouter, $method, $url, $handler, function ($_) {
                return array('Error 1!', 'Error 2!');
            });
            $this->fail('Do not continue if spec fails');
        } catch (Exception $e) {
            $this->assertEmpty($this->fakeRouter->routes(), 'Do not add route on spec failure');
            $this->assertEquals('Error 1! Error 2!', $e->getMessage());
            $this->assertEquals(Common\errorCodes()['SPEC_FAILURE'], $e->getCode());
        }
    }
}
