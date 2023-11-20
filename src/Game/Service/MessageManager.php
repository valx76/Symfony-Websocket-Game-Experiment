<?php

namespace App\Game\Service;

use App\Game\Contracts\MessageInterface;
use App\Game\Exception\IncorrectMessageFormatException;
use App\Game\Exception\MessageNotFoundException;
use App\Game\GameServer;
use Ratchet\ConnectionInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class MessageManager
{
    /**
     * @var MessageInterface[] $messages
     */
    private array $messages;

    /**
     * @param MessageInterface[] $messages
     */
    public function __construct(
        #[TaggedIterator('app.message')] iterable $messages
    )
    {
        $this->messages = $messages instanceof \Traversable ? iterator_to_array($messages): $messages;
    }

    /**
     * @return MessageInterface[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @throws IncorrectMessageFormatException
     * @throws MessageNotFoundException
     */
    public function processMessage(GameServer $gameServer, ConnectionInterface $connection, string $jsonMessageContent): void
    {
        $messageContent = json_decode($jsonMessageContent, true);

        if ($messageContent === null || !isset($messageContent['id'], $messageContent['content']) || !is_numeric($messageContent['id'])) {
            throw new IncorrectMessageFormatException();
        }

        $messageId = intval($messageContent['id']);
        $content = $messageContent['content'];

        foreach ($this->messages as $message) {
            if ($message->supports($messageId)) {
                $message->process($gameServer, $connection, $content);
                return;
            }
        }

        throw new MessageNotFoundException();
    }
}