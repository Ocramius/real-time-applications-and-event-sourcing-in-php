<?php

declare(strict_types=1);

namespace ESBowling\Aggregate;

use ESBowling\DomainEvent\FoulRecorded;
use ESBowling\DomainEvent\FrameCompleted;
use ESBowling\DomainEvent\GameCompleted;
use ESBowling\DomainEvent\GameStarted;
use ESBowling\DomainEvent\SpareRecorded;
use ESBowling\DomainEvent\StrikeRecorded;
use ESBowling\DomainEvent\ThrowRecorded;
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
        $this->assertCanThrow();

        if (rand(0, 10) < 1) {
            $this->recordThrow(ThrowRecorded::fromGameIdAndFoul($this->id));

            return;
        }

        $this->recordThrow(ThrowRecorded::fromGameIdAndPinsHit($this->id, rand(0, 10)));
    }

    /**
     * note: functional reactive frameworks make this much easier:
     */
    private function recordThrow(ThrowRecorded $throw)
    {
        $isFirstFrameShot = $this->isFrameFirstShot();

        $this->gameEvents[] = $throw;

        if ($throw->isIsFoul()) {
            $this->gameEvents[] = FoulRecorded::fromGameId($this->id);
        }

        if ($throw->hasStrikeScore() && $isFirstFrameShot) {
            $this->gameEvents[] = StrikeRecorded::fromGameId($this->id);
        }

        // note: we completely skip the rules of throws 11 and 12, because they are a mess.
    }

    private function assertCanThrow()
    {
        if ($this->getEventsByType(GameCompleted::class)) {
            throw new \DomainException('Cannot throw in a completed game!');
        }
    }

    /**
     * Whether the current shot is the first one of the current frame or not
     */
    private function isFrameFirstShot() : bool
    {
        $throwsSoFar     = $this->countEventsByType(ThrowRecorded::class);
        $strikesSoFar    = $this->countEventsByType(StrikeRecorded::class);
        $framesCompleted = $this->countEventsByType(FrameCompleted::class);
        $spareOrLessFrames = $framesCompleted - $strikesSoFar;
        $spareOrLessThrows = $throwsSoFar - $strikesSoFar;

        return ! (($spareOrLessThrows - $spareOrLessFrames) % 2);
    }

    private function countEventsByType(string $className) : int
    {
        return count($this->getEventsByType($className));
    }

    private function getEventsByType(string $className) : array
    {
        return array_values(array_filter(
            $this->gameEvents,
            function ($event) use ($className) {
                return $event instanceof $className;
            }
        ));
    }
}
