<?php
require 'vendor/autoload.php';
use React\Http\Server;
use Psr\Http\Message\ServerRequestInterface;
use App\Router;

$loop = React\EventLoop\Factory::create();
$router = new Router($loop);
$router->load(__DIR__ . '/routes.php');
$server = new Server(
    $loop,
    function (ServerRequestInterface $request) use ($router) {
        return $router($request);
    }
);

$socket = new React\Socket\Server("0.0.0.0:80", $loop);
$server->listen($socket);
echo 'Работает на '
    . str_replace('tcp:', 'http:', $socket->getAddress())
    . PHP_EOL;
$loop->run();