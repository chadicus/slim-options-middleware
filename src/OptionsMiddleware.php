<?php

namespace SubjectivePHP\Slim\Middleware;

use Fig\Http\Message\RequestMethodInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Method;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\NotFoundException;

final class OptionsMiddleware implements RequestMethodInterface
{
    /**
     * @var string
     */
    private $origin;

    /**
     * @var string[]
     */
    private $headers;

    /**
     * Create a new instance of OptionsMiddleware.
     *
     * @param string   $origin  Value for the Access-Control-Allow-Origin header.
     * @param string[] $headers Value for the Access-Control-Allow-Headers header.
     */
    public function __construct(string $origin, array $headers)
    {
        $this->origin = $origin;
        $this->headers = $headers;
    }

    /**
     * Invoke this middleware.
     *
     * @param ServerRequestInterface $request  The incoming HTTP request.
     * @param ResponseInterface      $response The outgoing HTTP response.
     * @param callable               $next     The next middleware in the stack.
     *
     * @return ResponseInterface
     *
     * @throws NotFoundException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) : ResponseInterface
    {
        if ($request->getMethod() !== self::METHOD_OPTIONS) {
            return $next($request, $response);
        }

        $route = $request->getAttribute('route');
        if (empty($route)) {
            throw new NotFoundException($request, $response);
        }

        $methods = $route->getMethods();
        $methods[] = self::METHOD_OPTIONS;
        return $response->withHeader('Access-Control-Allow-Origin', $this->origin)
            ->withHeader('Access-Control-Allow-Headers', implode(',', $this->headers))
            ->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
    }
}
