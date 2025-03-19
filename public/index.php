<?php

use Framework\Http\RequestFactory;
use Framework\Http\Response;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';
function print_pre($var, $flag = 0)
{
    echo '<pre>' . print_r($var, 1) . '</pre>';
    if ($flag !== 0) {exit();}
}

$request = RequestFactory::fromGlobals();

$name = $request->getQueryParams()['name'] ?? 'Guest';

$response = (new Response('Hello, ' . $name . '!'))
    ->withHeader('X-Developer', 'AlexSaM');

header('HTTP/1.0 ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
foreach ($response->getHeaders() as $name => $values) {
    header($name . ': ' . $values);
}

echo $response->getBody();
