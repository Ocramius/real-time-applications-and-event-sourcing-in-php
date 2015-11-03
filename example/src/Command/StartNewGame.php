<?php

namespace ESBowling;

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
