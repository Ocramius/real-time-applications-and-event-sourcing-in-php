<?php

declare(strict_types=1);

namespace ESBowling\DomainEvent;

use Ramsey\Uuid\UuidInterface as Uuid;

final class StrikeRecorded implements GameEventInterface
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

    public function getGameId() : Uuid
    {
        return $this->gameId;
    }
}
