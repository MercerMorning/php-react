<?php
require 'vendor/autoload.php';
use React\Http\Server;
use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
$loop = React\EventLoop\Factory::create();
$server = new Server(
    $loop,
    function (ServerRequestInterface $request) {
        return new Response(
            200,
            ['Content-Type' => 'text/plain; charset=UTF-8'],
            'Привет, мир'
        );
    }
);
$socket = new React\Socket\Server("0.0.0.0:80", $loop);
$server->listen($socket);
echo 'Работает на '
    . str_replace('tcp:', 'http:', $socket->getAddress())
    . PHP_EOL;
$loop->run();