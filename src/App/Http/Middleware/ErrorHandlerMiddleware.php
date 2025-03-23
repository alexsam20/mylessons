<?php

namespace App\Http\Middleware;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

readonly class ErrorHandlerMiddleware
{
    public function __construct(private bool $debug = false)
    { }

    /**
     * @param ServerRequestInterface $request
     * @param callable $next
     * @return HtmlResponse|JsonResponse
     */
    public function __invoke(ServerRequestInterface $request, callable $next): JsonResponse|HtmlResponse
    {
        try {
            return $next($request);
        } catch (Throwable $e) {
            if ($this->debug) {
                return new JsonResponse([
                    'error' => 'Server error',
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    /*'trace' => $e->getTrace(),*/
                ], 500);
            }
            return new HtmlResponse('Server error', 500);
        }
    }
}
