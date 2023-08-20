<?php
declare(strict_types=1);


namespace App\Controller;

use App\ChildProcessFactory;
use Psr\Http\Message\ServerRequestInterface;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use React\Http\Message\Response;

class Index
{
    public function __construct(ChildProcessFactory $childProcesses)
    {
        $this->childProcesses = $childProcesses;
    }

    public function __invoke(
        ServerRequestInterface $request, LoopInterface $loop
    ) {
        $listFiles = $this->childProcesses->create('ls uploads');
        $listFiles->start($loop);
        $renderPage = $this->childProcesses->create('php pages/index.php');
        $renderPage->start($loop);
        $listFiles->stdout->pipe($renderPage->stdin);
        return new Response(
            200,
            ['Content-Type' => 'text/html; charset=UTF-8'],
            $renderPage->stdout
        );
    }
}