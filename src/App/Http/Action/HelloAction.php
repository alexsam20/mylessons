<?php

namespace App\Http\Action;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

class HelloAction
{
    /**
     * @param ServerRequestInterface $request
     * @return HtmlResponse
     */
    public function __invoke(ServerRequestInterface $request): HtmlResponse
    {
        $name = $request->getQueryParams()['name'] ?? 'Guest';

        return new HtmlResponse('Hello, ' . $name . '!');
    }
}
