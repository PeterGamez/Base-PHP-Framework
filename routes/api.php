<?php

use App\Class\Api;

header('Content-Type: application/json; charset=UTF-8');

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
