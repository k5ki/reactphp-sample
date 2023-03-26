<?php

require __DIR__ . '/vendor/autoload.php';

use React\Http\Message\Response;

$hello = function (Psr\Http\Message\ServerRequestInterface $request) {
    return new Response(
        Response::STATUS_OK,
        ['Content-Type' => 'text/plain'],
        "Hello World!\n"
    );
};

$http = new React\Http\HttpServer($hello);
$socket = new React\Socket\SocketServer('0.0.0.0:8080');
$http->listen($socket);

echo "Listening on" . str_replace('tcp:', 'http:', $socket->getAddress()) . PHP_EOL;
