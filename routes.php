<?php

use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;

return [
    '/' => function (
        ServerRequestInterface $request, LoopInterface $loop
    ) {
        $childProcess = new Process('cat pages/index.html', __DIR__);
        $childProcess->start($loop);

        return new Response(
            200,
            ['Content-Type' => 'text/html; charset=UTF-8'],
            $childProcess->stdout
        );
    },
    '/upload' => function (
        ServerRequestInterface $request, LoopInterface $loop
    ) {
        /** @var \Psr\Http\Message\UploadedFileInterface $file */
        $file = $request->getUploadedFiles()['file'];
        $process = new Process(
            "cat > uploads/{$file->getClientFilename()}", __DIR__
        );
        $loop->addPeriodicTimer(
            1,
            function () use ($process) {
                echo 'Дочерний процесс ';
                echo $process->isRunning()
                    ? 'выполняется'
                    : 'остановлен';
                echo PHP_EOL;
            });
        $process->start($loop);
        $process->stdin->write($file->getStream()->getContents());
        $process->stdin->end();
        return new Response(
            200,
            ['Content-Type' => 'text/plain'],
            'Загрузка завершена'
        );
    }
];