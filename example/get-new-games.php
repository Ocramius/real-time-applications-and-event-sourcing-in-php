<?php

(function () {
    /* @var $repository \ESBowling\Repository\GameRepository */
    $repository = require __DIR__ . '/bootstrap-repository.php';

    header('Content-Type: application/json'); // no custom headers in the demo, may cause issues

    if (strtolower($_SERVER['REQUEST_METHOD']) !== 'get') {
        http_response_code(405);

        echo json_encode([
            'success' => false,
        ]);

        exit(1);
    }

    echo json_encode([
        'success' => true,
        'games'   => array_values(array_map(
            function (\ESBowling\DomainEvent\GameStarted $gameStarted) {
                return $gameStarted->getGameId()->toString();
            },
            isset($_GET['lastGameId'])
                ? $repository->getGameStartedEventsAfter(\Ramsey\Uuid\Uuid::fromString($_GET['lastGameId']))
                : $repository->getAllGameStartedEvents()
        )),
    ]);
})();
