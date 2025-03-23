<?php

namespace Framework\Http\Middleware;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Router;
use Psr\Http\Message\ServerRequestInterface;

readonly class RouteMiddleware
{
    public function __construct(private Router $router, private MiddlewareResolver $resolver)
    { }

    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, callable $next): mixed
    {
        try {
            $result = $this->router->match($request);
            foreach ($result->getAttributes() as $attribute => $value) {
                $request = $request->withAttribute($attribute, $value);
            }
            $middleware = $this->resolver->resolve($result->getHandler());
            return $middleware($request, $next);
        } catch (RequestNotMatchedException $e) {
            return $next($request);
        }
    }
}
