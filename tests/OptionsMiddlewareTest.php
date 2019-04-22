<?php

namespace SubjectivePHPTest\Slim\Middleware;

use Fig\Http\Message\RequestMethodInterface;
use PHPUnit\Framework\TestCase;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Stream;
use Slim\Http\Uri;
use Slim\Route;
use SubjectivePHP\Slim\Middleware\OptionsMiddleware;

/**
 * @coversDefaultClass \SubjectivePHP\Slim\Middleware\OptionsMiddleware
 * @covers ::__construct
 */
final class OptionsMiddlewareTest extends TestCase implements RequestMethodInterface
{
    /**
     * @var array
     */
    const ALLOW_HEADERS = ['Authorization, Content-Type'];

    /**
     * @var string
     */
    const ALLOW_ORIGIN = '*';

    /**
     * @var OptionsMiddleware
     */
    private $middleware;

    /**
     * @var callable
     */
    private $next;

    public function setUp()
    {
        $this->middleware = new OptionsMiddleware(self::ALLOW_ORIGIN, self::ALLOW_HEADERS);
        $this->next = function (Request $request, Response $response) : Response {
            return $response;
        };
    }

    /**
     * @test
     * @covers ::__invoke
     */
    public function invokeMethodNotOptions()
    {
        $request = $this->getRequest(self::METHOD_GET);
        $response = new Response();
        $this->assertSame($response, $this->middleware->__invoke($request, $response, $this->next));
    }

    /**
     * @test
     * @covers ::__invoke
     * @expectedException \Slim\Exception\NotFoundException
     */
    public function invokeMethodIsOptionsButNoRoute()
    {
        $request = $this->getRequest();
        $this->middleware->__invoke($request, new Response(), $this->next);
    }

    /**
     * @test
     * @covers ::__invoke
     */
    public function invoke()
    {
        $methods = [self::METHOD_GET, self::METHOD_POST];
        $route = new Route($methods, '/foo', $this->next);
        $request = $this->getRequest(self::METHOD_OPTIONS, $route);
        $response = $this->middleware->__invoke($request, new Response(), $this->next);
        $this->assertSame(
            [
                'Access-Control-Allow-Origin' => [
                    self::ALLOW_ORIGIN,
                ],
                'Access-Control-Allow-Headers' => [
                    implode(',', self::ALLOW_HEADERS),
                ],
                'Access-Control-Allow-Methods' => [
                    implode(',', [self::METHOD_GET, self::METHOD_POST, self::METHOD_OPTIONS]),
                ],
            ],
            $response->getHeaders()
        );
    }

    private function getRequest(string $method = self::METHOD_OPTIONS, Route $route = null) : Request
    {
        $uri = Uri::createFromString('http://localhost');
        $headers = new Headers();
        $resource = fopen('php://memory', 'r');
        $body = new Stream($resource);
        $request = new Request($method, $uri, $headers, [], [], $body);
        return $request->withAttribute('route',  $route);
    }
}
