<?php

declare(strict_types=1);

namespace ESBowling\DomainEvent;

use Ramsey\Uuid\Uuid;

final class GameCompleted
{
    /**
     * @var Uuid
     */
    private $gameId;

    private function __construct()
    {
    }

    public static function fromGameId(Uuid $gameId)
    {
        $instance = new self();

        $instance->gameId = $gameId;

        return $instance;
    }
}
