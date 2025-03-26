<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BasicAuthMiddleware
{
    public const string ATTRIBUTE = '_user';

    public function __construct(private readonly array $users) { }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $username = $request->getServerParams()['PHP_AUTH_USER'] ?? null;
        $password = $request->getServerParams()['PHP_AUTH_PW'] ?? null;

        if (!empty($username) && !empty($password)) {
            foreach ($this->users as $name => $pass) {
                if ($username === $name && $password === $pass) {
                    return $next($request->withAttribute(self::ATTRIBUTE, $name));
                }
            }
        }

        return $response
            ->withStatus(401)
            ->withHeader('WWW-Authenticate', 'Basic realm=Restricted area');    }
}
