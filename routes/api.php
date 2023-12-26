<?php

use App\Class\Api;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Respond to preflight requests (sent by some browsers before the actual request)
    http_response_code(200);
    exit();
}

$agent_request = array_slice($agent_request, 2);

$version = isset($agent_request[0]) ? $agent_request[0] : null;
array_shift($agent_request);

if (empty($version)) {
    echo Api::error_404('API Version Not Found');
    exit;
}

if ($version == 'v1') {
    if (empty($agent_request[0])) {
        echo Api::error_404('API Not Found');
        exit;
    }

    return api('GET', 'v1.index');
}

echo Api::error_404('API Not Found');
