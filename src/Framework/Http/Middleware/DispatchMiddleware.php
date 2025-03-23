<?php

namespace Framework\Http\Middleware;

use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\Result;
use Psr\Http\Message\ServerRequestInterface;

readonly class DispatchMiddleware
{
    public function __construct(private MiddlewareResolver $resolver)
    { }

    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, callable $next): mixed
    {
        /** @var Result $result */
        if (!$result = $request->getAttribute(Result::class)) {
            return $next($request);
        }
        $middleware = $this->resolver->resolve($result->getHandler());
        return $middleware($request, $next);
    }
}
