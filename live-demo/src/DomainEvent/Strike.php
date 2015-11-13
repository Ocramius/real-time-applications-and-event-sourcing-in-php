<?php

namespace LiveESBowling\DomainEvent;

final class Strike
{
    private function __construct()
    {
    }

    public static function strike() : self
    {
        return new self();
    }
}
