<?php

namespace LiveESBowling\DomainEvent;

final class Spare
{
    private function __construct()
    {
    }

    public static function spare() : self
    {
        return new self();
    }
}
