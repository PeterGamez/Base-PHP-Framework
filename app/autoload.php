<?php

require_once __ROOT__ . '/app/function.php';
require_once __ROOT__ . '/database/autoload.php';

loaddir(__ROOT__ . '/app/Class');
loaddir(__ROOT__ . '/app/Controllers');
loaddir(__ROOT__ . '/app/Models');

$agent = $_SERVER['HTTP_USER_AGENT'];
$agent_url = $_SERVER['REQUEST_URI'];
$agent_path = parse_url($agent_url, PHP_URL_PATH);
$agent_request = explode('/', $agent_path);

if (str_starts_with($agent_path, '/api')) {
    require_once __ROOT__ . '/routes/api.php';
} else {
    require_once __ROOT__ . '/routes/web.php';
}
