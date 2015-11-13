<?php

use LiveESBowling\Command\ThrowBall;

require_once __DIR__ . '/vendor/autoload.php';

$commandBus = require __DIR__ . '/command-bus.php';

$commandBus(ThrowBall::newThrow());

echo json_encode(true);
