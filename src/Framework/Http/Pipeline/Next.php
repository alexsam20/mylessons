<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SplQueue;

class Next
{
    private $next;
    public function __construct(private readonly SplQueue $queue, callable $next) {
        $this->next = $next;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            return ($this->next)($request);
        }

        $middleware = $this->queue->dequeue();

        return $middleware($request, function (ServerRequestInterface $request) {
            return $this($request);
        });
    }
}
