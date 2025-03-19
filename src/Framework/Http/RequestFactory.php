<?php

namespace Framework\Http;

class RequestFactory
{
    public static function fromGlobals(array $query = null, array $body = null): Request
    {
        $request = new Request();
        $request->withQueryParams($query ?: $_GET)->withParsedBody($body ?: $_POST);

        return $request;
    }
}
