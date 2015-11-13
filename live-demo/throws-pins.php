<?php

use LiveESBowling\Command\ThrowBall;

require_once __DIR__ . '/vendor/autoload.php';

$loadGame = require __DIR__ . '/load-game.php';

/* @var $game \LiveESBowling\Game */
$game = $loadGame();

echo json_encode([
    'number-of-throws'
        => $game->numberOfThrowsSoFar()
]);
