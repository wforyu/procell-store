<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

$_SERVER['APP_ENV'] = 'local';
$_SERVER['APP_DEBUG'] = 'true';
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$request = Request::capture();
try {
    $response = $kernel->handle($request);
    echo 'Status: '.$response->getStatusCode().PHP_EOL;
    echo 'Content length: '.strlen($response->getContent()).PHP_EOL;
    $content = $response->getContent();
    if ($content) {
        echo substr($content, 0, 2000).PHP_EOL;
    } else {
        echo 'EMPTY CONTENT'.PHP_EOL;
    }
    $kernel->terminate($request, $response);
} catch (Throwable $e) {
    echo 'Error: '.$e->getMessage().PHP_EOL;
    echo $e->getTraceAsString().PHP_EOL;
}
