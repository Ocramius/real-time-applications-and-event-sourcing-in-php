<?php

namespace LiveESBowling\DomainEvent;

final class GameStarted
{
    private function __construct()
    {
    }

    public static function started() : self
    {
        return new self();
    }
}
