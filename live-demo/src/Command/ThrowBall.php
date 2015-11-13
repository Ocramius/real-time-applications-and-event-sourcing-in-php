<?php

namespace LiveESBowling\Command;

final class ThrowBall
{
    private function __construct()
    {
    }

    public static function newThrow() : self
    {
        return new self();
    }
}
