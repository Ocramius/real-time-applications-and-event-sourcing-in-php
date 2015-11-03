<?php

declare(strict_types=1);

namespace ESBowling\DomainEvent;

use ESBowling\Aggregate\Game;
use Ramsey\Uuid\Uuid;

final class GameRepository
{
    /**
     * @var string
     */
    private $eventStoreFile;

    public function __construct(string $eventStoreFile)
    {
        $this->eventStoreFile = $eventStoreFile;
    }

    public function save(Game $game)
    {
        $gameEvents = $game->getRecordedDomainEvents();

        // assuming append-only, we only want to append events that aren't already in the storage (for this aggregate)
        $eventsToStore = array_values(array_slice($gameEvents, count($this->getGameEvents($game->getId()))));

        file_put_contents($this->eventStoreFile, serialize(array_merge($this->loadAllEvents(), $eventsToStore)));
    }

    private function getGameEvents(Uuid $gameId) : array
    {
        return array_values(array_filter(
            $this->loadAllEvents(),
            function ($event) use ($gameId) {
                return $event instanceof GameEventInterface
                    && $event->getGameId()->equals($gameId);
            }
        ));
    }

    /**
     * @return object[]
     */
    private function loadAllEvents() : array
    {
        return array_values(unserialize(file_get_contents($this->eventStoreFile)));
    }
}
