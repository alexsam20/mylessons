<?php

use Framework\HTTP\Request;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';
$request = new Request();

$name = $request->getQueryParams()['name'] ?? 'Guest';

function print_pre($var)
{
    echo '<pre>' . print_r($var, 1) . '</pre>';
}
header('X-Developer: AlexSaM');
echo 'Hello, ' . $name . '!' . PHP_EOL;
