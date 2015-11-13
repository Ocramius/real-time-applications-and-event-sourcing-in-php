<?php

namespace LiveESBowling\DomainEvent;

final class ThrowWasRecorded
{
    private $hitPins;

    private function __construct()
    {
    }

    public static function fromHitPins(int $pinsHit) : self
    {
        if ($pinsHit < 0 || $pinsHit > 10) {
            throw new \InvalidArgumentException(
                'There are only 10 pins, you muppet!'
            );
        }

        $instance = new self();

        $instance->hitPins = $pinsHit;

        return $instance;
    }

    public function getHitPins() : int
    {
        return $this->hitPins;
    }

    public function hitAllPins() : bool
    {
        return $this->hitPins === 10;
    }
}