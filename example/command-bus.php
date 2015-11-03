<?php

header('Content-Type: application/json'); // no custom headers in the demo, may cause issues

if (strtolower($_SERVER['REQUEST_METHOD']) !== 'post') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => sprintf('Unsupported HTTP method %s', $_SERVER['REQUEST_METHOD']),
    ]);

    exit(1);
}

switch ($_POST['command'] ?? null) {
    case 'NewGame':
        break;

    default:
        http_response_code(400);

        echo json_encode([
            'success' => false,
            'message' => sprintf('Unknown command %s requested', $_GET['command'] ?? null),
        ]);

        exit(1);
}

echo json_encode([
    'success' => true,
]);
