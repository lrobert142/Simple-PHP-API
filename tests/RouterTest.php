<?php declare(strict_types=1);

require_once(__DIR__ . '/../src/router.php');

use PHPUnit\Framework\TestCase;

final class RouterAddRouteTest extends TestCase
{
    function fakeSpec()
    {
        return true;
    }

    public function testNoSpec()
    {
        $method = 'GET';
        $url = '/test';
        $handler = 'testHandler';
        $router = new DefaultRouter();
        $router->addRoute($method, $url, $handler);
        $this->assertEquals(array(array(
            'handler' => $handler,
            'method' => $method,
            'spec' => null,
            'url' => $url,
        )), $router->routes());
    }

    public function testWithSpec()
    {
        $method = 'GET';
        $url = '/test';
        $handler = 'testHandler';
        $spec = array($this, 'fakeSpec');
        $router = new DefaultRouter();
        $router->addRoute($method, $url, $handler, $spec);
        $this->assertEquals(array(array(
            'handler' => $handler,
            'method' => $method,
            'spec' => $spec,
            'url' => $url,
        )), $router->routes());
    }

    public function testSameUrlDifferentMethod()
    {
        $url = '/test';
        $handler = 'testHandler';
        $spec = array($this, 'fakeSpec');
        $router = new DefaultRouter();
        $router->addRoute('GET', $url, $handler, $spec);
        $router->addRoute('POST', $url, $handler, $spec);
        $this->assertEquals(array(
            array(
                'handler' => $handler,
                'method' => 'GET',
                'spec' => $spec,
                'url' => $url,
            ),
            array(
                'handler' => $handler,
                'method' => 'POST',
                'spec' => $spec,
                'url' => $url,
            ),
        ), $router->routes());
    }

    public function testInvalidMethod()
    {
        $method = 'INVALID';
        $url = '/test';
        $handler = 'testHandler';
        $spec = array($this, 'fakeSpec');
        $router = new DefaultRouter();
        try {
            $router->addRoute($method, $url, $handler, $spec);
        } catch (Exception $e) {
            $this->assertEquals('Invalid request method.', $e->getMessage());
            $this->assertEquals(Common\errorCodes()['INVALID_REQUEST_METHOD'], $e->getCode());
        }
        $this->assertEmpty($router->routes());
    }

    public function testDuplicateRoutes()
    {
        $method = 'GET';
        $url = '/test';
        $handler = 'testHandler';
        $spec = array($this, 'fakeSpec');
        $router = new DefaultRouter();
        $router->addRoute($method, $url, $handler, $spec);
        try {
            $router->addRoute($method, $url, $handler, $spec);
        } catch (Exception $e) {
            $this->assertEquals('Cannot add duplicate method/route pair', $e->getMessage());
            $this->assertEquals(Common\errorCodes()['DUPLICATE_ROUTE'], $e->getCode());
        }
        $this->assertEquals(array(array(
            'handler' => $handler,
            'method' => $method,
            'spec' => $spec,
            'url' => $url,
        )), $router->routes());
    }
}

final class RouterDispatchTest extends TestCase
{
    public function testNoSpec()
    {
        $method = 'GET';
        $url = '/test';
        $params = array('foo' => 'bar');
        $handler = function ($args) use ($params) {
            $this->assertEquals($params, $args);
            return 'called';
        };

        $router = new DefaultRouter();
        $router->addRoute($method, $url, $handler);
        $this->assertEquals('called', $router->dispatch($method, $url, $params));
    }

    public function testPassSpec()
    {
        $method = 'GET';
        $url = '/test';
        $params = array('foo' => 'bar');
        $spec = function ($args) use ($params) {
            $this->assertEquals($params, $args);
            return array();
        };
        $handler = function ($args) use ($params) {
            $this->assertEquals($params, $args);
            return 'called';
        };

        $router = new DefaultRouter();
        $router->addRoute($method, $url, $handler, $spec);
        $this->assertEquals('called', $router->dispatch($method, $url, $params));
    }

    public function testFailSpec()
    {
        $method = 'GET';
        $url = '/test';
        $params = array('foo' => 'bar');
        $spec = function ($args) use ($params) {
            $this->assertEquals($params, $args);
            return array('Failure1', 'Failure2');
        };
        $handler = function ($_) {
            $this->fail('Do not call handler on failed spec');
        };

        $router = new DefaultRouter();
        $router->addRoute($method, $url, $handler, $spec);
        try {
            $router->dispatch($method, $url, $params);
        } catch (Exception $e) {
            $this->assertEquals('Failure1 Failure2', $e->getMessage());
            $this->assertEquals(Common\errorCodes()['SPEC_FAILURE'], $e->getCode());
        }
    }

    public function testInvalidRoute()
    {
        $url = '/test';
        $params = array('foo' => 'bar');
        $spec = function ($_) {
            $this->fail('Do not call spec on invalid route');
        };
        $handler = function ($_) {
            $this->fail('Do not call handler on failed spec');
        };

        $router = new DefaultRouter();
        $router->addRoute('GET', $url, $handler, $spec);
        try {
            $router->dispatch('POST', $url, $params);
        } catch (Exception $e) {
            $this->assertEquals('Requested route not found', $e->getMessage());
            $this->assertEquals(Common\errorCodes()['ROUTE_NOT_FOUND'], $e->getCode());
        }
    }
}
