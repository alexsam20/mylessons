<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SplQueue;

class Pipeline
{
    public function __construct(private $queue = new SplQueue()) { }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $delegate = new Next(clone $this->queue, $next);
        return $delegate($request, $response);
    }


    /**
     * @param callable $middleware
     * @return void
     */
    public function pipe(callable $middleware): void
    {
        $this->queue->enqueue($middleware);
    }
}
