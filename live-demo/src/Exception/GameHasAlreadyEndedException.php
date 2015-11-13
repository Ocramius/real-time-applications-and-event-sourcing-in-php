<?php

namespace LiveESBowling\Exception;

class GameHasAlreadyEndedException extends \DomainException
{
    public static function fromExcessiveThrows(int $currentThrows)
    {
        return new self(sprintf(
            'The game already ended with %s throws',
            $currentThrows
        ));
    }

    public static function gameComplete()
    {
        return new self('The game is complete');
    }
}
