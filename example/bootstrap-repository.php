<?php

declare(strict_types=1);

use ESBowling\Repository\GameRepository;

return (function () {
    require_once __DIR__ . '/vendor/autoload.php';

    $savePath = __DIR__ . '/saved-games/event-store.dat';

    if (! file_exists($savePath)) {
        file_put_contents($savePath, serialize([]));
    }

    return new GameRepository(realpath($savePath));
})();
