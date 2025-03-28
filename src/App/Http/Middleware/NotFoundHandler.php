<?php

namespace App\Http\Middleware;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class NotFoundHandler
{
    public function __invoke(ServerRequestInterface $request): HtmlResponse
    {
        return new HtmlResponse('Undefined page', 404);
    }
}
