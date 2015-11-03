<?php

declare(strict_types=1);

namespace ESBowling\DomainEvent;

use Ramsey\Uuid\UuidInterface as Uuid;

final class ThrowRecorded implements GameEventInterface
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

    public function getGameId() : Uuid
    {
        return $this->gameId;
    }

    /**
     * @return int
     */
    public function getPinsHit()
    {
        return $this->pinsHit;
    }

    /**
     * @return bool
     */
    public function hasStrikeScore()
    {
        return 10 === $this->pinsHit && ! $this->isFoul;
    }

    /**
     * @return boolean
     */
    public function getIsFoul()
    {
        return $this->isFoul;
    }
}
