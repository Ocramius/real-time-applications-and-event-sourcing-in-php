<?php

declare(strict_types=1);

namespace ESBowling\Repository;

use ESBowling\Aggregate\Game;
use ESBowling\DomainEvent\GameEventInterface;
use ESBowling\DomainEvent\GameStarted;
use Ramsey\Uuid\UuidInterface as Uuid;

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

    public function get(Uuid $gameId) : Game
    {
        return Game::fromEvents($this->getGameEvents($gameId));
    }

    /**
     * @return GameStarted[]
     */
    public function getAllGameStartedEvents() : array
    {
        return array_values(array_filter(
            $this->loadAllEvents(),
            function ($event) {
                return $event instanceof GameStarted;
            }
        ));
    }

    public function getGameStartedEventsAfter(Uuid $gameId) : array
    {
        $allGameStartedEvents = $this->getAllGameStartedEvents();

        $index = 0;

        foreach ($allGameStartedEvents as $gameStartedIdx => $gameStarted) {
            if ($gameStarted->getGameId()->equals($gameId)) {
                $index = $gameStartedIdx;

                break;
            }
        }

        return array_values(array_slice($allGameStartedEvents, $index));
    }

    /**
     * @param Uuid $gameId
     *
     * @return GameEventInterface[]
     */
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
