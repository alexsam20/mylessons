<?php

namespace App\Http\Middleware;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CredentialsMiddleware
{
    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     * @return MessageInterface|ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, callable $next): MessageInterface|ResponseInterface
    {
        /** @var ResponseInterface $response */
        $response = $next($request);
        return $response->withHeader('X-Developer', 'AlexSaM');
}
}
