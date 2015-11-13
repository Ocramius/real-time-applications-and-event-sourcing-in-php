<?php

namespace LiveESBowling;

use LiveESBowling\DomainEvent\GameStarted;
use LiveESBowling\DomainEvent\Spare;
use LiveESBowling\DomainEvent\Strike;
use LiveESBowling\DomainEvent\ThrowWasRecorded;
use LiveESBowling\Exception\GameHasAlreadyEndedException;
use LiveESBowling\DomainEvent\FrameCompleted;

class Game
{
    private $events = [];

    private function __construct()
    {
    }

    public static function newGame() : self
    {
        $instance = new self();

        $instance->events[] = GameStarted::started();

        return $instance;
    }

    public static function fromEvents(array $events) : self
    {
        $started = reset($events);

        if (! $started instanceof GameStarted) {
            throw new \UnexpectedValueException('Not started?');
        }

        $instance = new self();

        $instance->events = array_values($events);

        return $instance;
    }

    public function getRecordedEvents() : array
    {
        return $this->events;
    }

    public function throwBall()
    {
        if ($this->numberOfFramesSoFar() >= 10) {
            throw GameHasAlreadyEndedException::gameComplete();
        }

        $max = 10;

        $isFrameEmpty = $this->getHitsCountSinceLastFrame() % 2;

        if (! $isFrameEmpty) {
            $max -= array_sum(array_map(
                function (ThrowWasRecorded $throwWasRecorded) {
                    return $throwWasRecorded->getHitPins();
                },
                $this->getHitsSinceLastFrame()
            ));
        }

        $hitRecorded = ThrowWasRecorded::fromHitPins(rand(0, $max));

        $this->events[] = $hitRecorded;

        if ($isFrameEmpty && $hitRecorded->hitAllPins()) {
            $this->events[] = Strike::strike();
        }

        if (! $isFrameEmpty && ! ($max - $hitRecorded->getHitPins())) {
            $this->events[] = Spare::spare();
        }

        if (0 === ($this->getHitsCountSinceLastFrame() % 2)) {
            $this->events[] = FrameCompleted::frameCompleted();
        }

        // if ($this->GameIsComplete()) { $this->events[] = GameCompleted::fromGame($this); }
    }

    public function numberOfStrikes() : int
    {
        return count(array_filter(
            $this->events,
            function ($event) {
                return $event instanceof Strike;
            }
        ));
    }

    public function numberOfThrowsSoFar() : int
    {
        return count($this->getAllHitEvents());
    }

    public function numberOfFramesSoFar() : int
    {
        return count(array_filter(
            $this->events,
            function ($event) {
                return $event instanceof FrameCompleted;
            }
        ));
    }

    public function individualHitScore() : array
    {
        return array_values(array_map(
            function (ThrowWasRecorded $throwWasRecorded) {
                return $throwWasRecorded->getHitPins();
            },
            $this->getAllHitEvents()
        ));
    }

    /** @return ThrowWasRecorded[] */
    private function getAllHitEvents() : array
    {
        return array_values(array_filter(
            $this->events,
            function ($event) {
                return $event instanceof ThrowWasRecorded;
            }
        ));
    }

    private function getHitsCountSinceLastFrame() : int
    {
        return count($this->getHitsSinceLastFrame());
    }

    private function getHitsSinceLastFrame() : array
    {
        $throws = [];

        foreach (array_reverse($this->events) as $event) {
            if ($event instanceof FrameCompleted) {
                return $throws;
            }

            if ($event instanceof ThrowWasRecorded) {
                $throws[] = $event;
            }
        }

        return [];
    }
}
