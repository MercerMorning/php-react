<?php

use App\Controller\Download;
use App\Controller\Index;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;

$childProcessFactory = new \App\ChildProcessFactory(__DIR__);

return [
    '/download/uploads/.*\.(jpg|png)$' => new Download($childProcessFactory),
    '/list' => function (
        ServerRequestInterface $request, LoopInterface $loop
    ) {
        $listFiles = new Process('ls uploads', __DIR__);
        $listFiles->start($loop);
        $renderPage = new Process('php pages/list.php', __DIR__);
        $renderPage->start($loop);
        $listFiles->stdout->pipe($renderPage->stdin);
        return new Response(
            200,
            ['Content-Type' => 'text/html; charset=UTF-8'],
            $renderPage->stdout
        );
    },
    '/uploads/.*\.(jpg|png)$' => new \App\Controller\Preview(),
    '/' => new Index($childProcessFactory),
    '/upload' => new \App\Controller\Upload(),

];