<?php

header('Content-Type: application/json'); // no custom headers in the demo, may cause issues

if (strtolower($_SERVER['REQUEST_METHOD']) !== 'get') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
    ]);

    exit(1);
}

$games = [
    'abcdef',
    'hijklm',
];

$gamesIndex = array_search(
    $_GET['lastGameId'] ?? null,
    $games,
    true
);

echo json_encode([
    'success' => true,
    'games'   => array_slice($games, false === $gamesIndex ? 0 : $gamesIndex + 1),
]);
