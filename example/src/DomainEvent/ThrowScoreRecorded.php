<?php

declare(strict_types=1);

namespace ESBowling\DomainEvent;

use Ramsey\Uuid\Uuid;

final class ThrowScoreRecorded
{
    /**
     * @var Uuid
     */
    private $gameId;

    /**
     * @var int
     */
    private $score;

    private function __construct()
    {
    }

    public static function fromGameIdAndScore(Uuid $gameId, int $score)
    {
        $instance = new self();

        $instance->gameId = $gameId;
        $instance->score  = (int) $score;

        return $instance;
    }
}
