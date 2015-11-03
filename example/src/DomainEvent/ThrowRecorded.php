<?php

declare(strict_types=1);

namespace ESBowling\DomainEvent;

use Ramsey\Uuid\Uuid;

final class ThrowRecorded
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

    public static function fromGameIdAndPinsHit(Uuid $gameId, int $pinsHit)
    {
        $instance = new self();

        $instance->gameId  = $gameId;
        $instance->pinsHit = (int) $pinsHit;

        return $instance;
    }

    public static function fromGameIdAndFoul(Uuid $gameId)
    {
        $instance = new self();

        $instance->gameId = $gameId;
        $instance->isFoul = true;

        return $instance;
    }
}