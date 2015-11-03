<?php

use Ramsey\Uuid\Uuid;

(function () {
    /* @var $repository \ESBowling\Repository\GameRepository */
    $repository = require __DIR__ . '/bootstrap-repository.php';

    header('Content-Type: application/json'); // no custom headers in the demo, may cause issues

    if (strtolower($_SERVER['REQUEST_METHOD']) !== 'get') {
        http_response_code(405);

        echo json_encode([
            'success' => false,
            'message' => 'Only GET is supported by this enpdoint',
        ]);

        exit(1);
    }

    if (! (isset($_GET['gameId']) && isset($_GET['offset']))) {
        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => sprintf('Missing parameter %s', isset($_GET['gameId']) ? 'offset' : 'gameId'),
        ]);

        exit(1);
    }

    $extractEventProperties = function ($event) : array {
        $data = [];

        foreach ((new \ReflectionClass($event))->getProperties() as $key => $property) {
            $property->setAccessible(true);

            $value = $property->getValue($event);

            if ($value instanceof \Ramsey\Uuid\UuidInterface) {
                $value = $value->toString();
            }

            $data[$key] = $value;
        }

        return $data;
    };

    $game = $repository->get(Uuid::fromString($_GET['gameId']));

    echo json_encode([
        'success' => true,
        'gameId'  => $game->getId()->toString(),
        'events'  => array_map(
            function ($event) use ($extractEventProperties) {
                return [
                    'type' => get_class($event),
                    'data' => $extractEventProperties($event),
                ];
            },
            array_values(array_slice($game->getRecordedDomainEvents(), (int) $_GET['offset']))
        )
    ]);
})();
