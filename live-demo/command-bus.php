<?php

// command bus!
use LiveESBowling\Command\ThrowBall;

return function ($command) {
    $loadGame = require __DIR__ . '/load-game.php';
    $saveGame = require __DIR__ . '/save-game.php';

    if ($command instanceof ThrowBall) {
        /* @var $game \LiveESBowling\Game */
        $game = $loadGame();

        $game->throwBall();

        $saveGame($game);

        return;
    }

    throw new \InvalidArgumentException(sprintf(
        'Unrecognized command of type "%s" given',
        is_object($command) ? get_class($command) : gettype($command)
    ));
};
