<?php

declare(strict_types=1);

namespace ESBowling\Aggregate;

use ESBowling\DomainEvent\GameStarted;
use Ramsey\Uuid\Uuid;

final class Game
{
    /**
     * @var Uuid
     */
    private $id;

    /**
     * @var object[]
     */
    private $gameEvents = [];

    private function __construct()
    {
    }

    public static function newGame()
    {
        $instance = new self();

        $instance->id           = Uuid::uuid4();
        $instance->gameEvents[] = GameStarted::fromGameId($instance->id);

        return $instance;
    }

    public static function fromEvents(array $events)
    {
        $firstEvent = reset($events);

        if (! $firstEvent instanceof GameStarted) {
            throw new \UnexpectedValueException('The provided events don\'t start with the game start');
        }

        $instance = new self();

        $instance->id         = $firstEvent->getGameId();
        $instance->gameEvents = array_values($events);

        return $instance;
    }

    /**
     * @return void
     */
    public function throwBall()
    {
    }
}
