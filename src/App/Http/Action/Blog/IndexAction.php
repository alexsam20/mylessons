<?php

namespace App\Http\Action\Blog;

use Laminas\Diactoros\Response\JsonResponse;

class IndexAction
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            ['id' => 2, 'title' => 'The Second Page'],
            ['id' => 1, 'title' => 'The First Page'],
        ]);
    }
}
