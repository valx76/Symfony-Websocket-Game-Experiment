<?php

namespace App\Game\EventListener;

use App\Game\Event\WebsocketErrorEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class WebsocketErrorEventListener
{
    public function __invoke(WebsocketErrorEvent $event): void
    {
        var_dump('Websocket error!');
    }
}