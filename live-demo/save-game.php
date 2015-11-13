<?php

use LiveESBowling\Game;

return function (Game $game) {
    file_put_contents(
        __DIR__ . '/game/game-events.sav',
        serialize($game->getRecordedEvents())
    );
};
