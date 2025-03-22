<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SplQueue;

class Pipeline
{
    public function __construct(private $queue = new SplQueue())
    {
    }


    /**
     * @param callable $middleware
     * @return void
     */
    public function pipe(callable $middleware): void
    {
        $this->queue->enqueue($middleware);
    }

    /**
     * @param ServerRequestInterface $request
     * @param callable $default
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, callable $default): ResponseInterface
    {
        return $this->next($request, $default);
    }

    /**
     * @param ServerRequestInterface $request
     * @param callable $default
     * @return ResponseInterface
     */
    public function next(ServerRequestInterface $request, callable $default): ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            return $default($request);
        }

        $current = $this->queue->dequeue();

        return $current($request, function (ServerRequestInterface $request) use ($default) {
            return $this->next($request, $default);
        });
    }
}
