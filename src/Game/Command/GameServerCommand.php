<?php

namespace App\Game\Command;

use App\Game\Controller\WebsocketController;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'GameServerCommand',
    description: 'Start the server'
)]
class GameServerCommand extends Command
{
    public function __construct(
        #[Autowire('%env(int:APP_PORT)%')]
        private readonly int $port,

        private readonly WebsocketController $websocketController,
        string $name = null
    )
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    $this->websocketController
                )
            ),
            $this->port
        );

        $server->run();

        return Command::SUCCESS;
    }
}