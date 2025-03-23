<?php

namespace Framework\Http;


use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Pipeline\Pipeline;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Application extends Pipeline
{
    private $default;

    public function __construct(private readonly MiddlewareResolver $resolver, callable $default)
    {
        parent::__construct();
        $this->default = $default;
    }

    /**
     * @param $middleware
     * @return void
     */
    public function pipe($middleware): void
    {
        parent::pipe($this->resolver->resolve($middleware));
    }

    public function run(ServerRequestInterface $request): ResponseInterface
    {
        return $this($request, $this->default);
    }
}
