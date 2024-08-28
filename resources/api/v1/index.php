<?php

use System\Router\Respond;

$respond = [
    'code' => 200,
    'message' => 'OK',
];

Respond::json($respond);
