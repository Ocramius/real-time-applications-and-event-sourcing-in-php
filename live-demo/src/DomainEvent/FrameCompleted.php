<?php

namespace LiveESBowling\DomainEvent;

final class FrameCompleted
{
    private function __construct()
    {
    }

    public static function frameCompleted() : self
    {
        return new self();
    }
}
