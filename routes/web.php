<?php

use App\Class\App;

array_shift($agent_request);

if (empty($agent_request[0]) and App::isGET()) {
    return visitor_views('index');
}