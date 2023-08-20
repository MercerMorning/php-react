<?php
declare(strict_types=1);


namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use React\Http\Message\Response;

class Upload
{
    public function __invoke(
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
        return new Response(302, ['Location' => '/list']);
    }
}