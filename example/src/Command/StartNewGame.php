<?php

declare(strict_types=1);

namespace ESBowling\Command;

final class StartNewGame
{
    private function __construct()
    {
    }

    /**
     * @return self
     */
    public static function fromPostData()
    {
        return new self();
    }
}
