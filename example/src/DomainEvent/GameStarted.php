<?php

declare(strict_types=1);

namespace ESBowling\DomainEvent;

use Ramsey\Uuid\Uuid;

final class GameStarted
{
    /**
     * @var Uuid
     */
    private $gameId;

    /**
     * @var int
     */
    private $pinsHit = 0;

    /**
     * @var bool
     */
    private $isFoul = false;

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
