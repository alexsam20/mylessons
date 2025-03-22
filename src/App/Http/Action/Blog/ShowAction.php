<?php

namespace App\Http\Action\Blog;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class ShowAction
{
    /**
     * @param ServerRequestInterface $request
     * @return JsonResponse|HtmlResponse
     */
    public function __invoke(ServerRequestInterface $request): JsonResponse|HtmlResponse
    {
        $id = $request->getAttribute('id');

        if ($id > 2) {
            return new HtmlResponse('Undefined page', 404);
        }

        return new JsonResponse(['id' => $id, 'title' => 'Post # ' . $id]);
    }
}
