<?php

use System\Router\Request;
use System\Router\Respond;
use System\Router\Route;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=UTF-8');

// Respond to preflight requests (sent by some browsers before the actual request)
Route::options('*', function (Request $request, string $patten) {
    Respond::status(200);
});

Route::get('/', function (Request $request) {
    Respond::json(['code' => 200, 'message' => 'Welcome to the API']);
});

Route::group('v1', function () {
    Route::get('/', function (Request $request) {
        api('v1.index');
    });
});

Respond::status(404)::json(['code' => 404, 'error' => 'API Version Not Found']);
