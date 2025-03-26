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
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            return ($this->next)($request, $response);
        }

        $middleware = $this->queue->dequeue();

        return $middleware($request, $response, function (ServerRequestInterface $request) use ($response) {
            return $this($request, $response);
        });
    }
}
