<?php

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
    'games'   => [
        'abcdef',
        'hijklm',
    ],
]);
