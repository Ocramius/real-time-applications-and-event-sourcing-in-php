<?php

declare(strict_types=1);

use ESBowling\Command\StartNewGame;
use ESBowling\Command\ThrowBall;
use ESBowling\CommandHandler\StartNewGameHandler;
use ESBowling\CommandHandler\ThrowBallHandler;
use ESBowling\Repository\GameRepository;

return (function () {
    require_once __DIR__ . '/vendor/autoload.php';

    /* @var $repository GameRepository */
    $repository = require __DIR__ . '/bootstrap-repository.php';

    return function ($command) use ($repository) {
        if ($command instanceof StartNewGame) {
            (new StartNewGameHandler($repository))->__invoke($command);

            return;
        }

        if ($command instanceof ThrowBall) {
            (new ThrowBallHandler($repository))->__invoke($command);

            return;
        }

        throw new \UnexpectedValueException(sprintf(
            'Unrecognized command of type "%s"',
            is_object($command) ? get_class($command) : gettype($command))
        );
    };
})();
