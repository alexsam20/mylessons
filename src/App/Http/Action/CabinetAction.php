<?php

namespace App\Http\Action;

use App\Http\Middleware\BasicAuthMiddleware;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ServerRequestInterface;

readonly class CabinetAction
{
    /**
     * @param ServerRequestInterface $request
     * @return HtmlResponse
     */
    public function __invoke(ServerRequestInterface $request): HtmlResponse
    {
        $username = $request->getAttribute(BasicAuthMiddleware::ATTRIBUTE);

        return new HtmlResponse('I am logged in as ' . $username);
    }
}
