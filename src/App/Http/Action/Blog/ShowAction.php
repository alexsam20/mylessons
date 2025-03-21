<?php

namespace App\Http\Action\Blog;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class ShowAction
{
    /**
     * @param ServerRequestInterface $request
     * @return JsonResponse
     */
    public function __invoke(ServerRequestInterface $request): JsonResponse
    {
        $id = $request->getAttribute('id');

        if ($id > 5) {
            return new JsonResponse(['error' => 'Undefined page'], 404);
        }

        return new JsonResponse(['id' => $id, 'title' => 'Post # ' . $id]);
    }
}
