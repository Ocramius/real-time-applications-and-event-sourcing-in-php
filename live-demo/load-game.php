<?php

use LiveESBowling\Game;

return function () : Game {
    if (file_exists(__DIR__ . '/game/game-events.sav')) {
        return Game::fromEvents(unserialize(file_get_contents(
            __DIR__ . '/game/game-events.sav'
        )));
    }

    return Game::newGame();
};
