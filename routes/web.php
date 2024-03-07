<?php

use App\Controllers\View;
use App\Models\Account;
use System\Router\Request;
use System\Router\Respond;
use System\Router\Route;

Route::get('/', function (Request $request) {
    Respond::text('Hello, World!');
});

Route::get('/request/*', function (Request $request, string $patten = null) {
    Respond::json($request);
});

Route::post('/create', function (Request $request) {
    $validate = $request->validate([
        'name' => 'required|min:3|max:10',
        'email' => 'required|email'
    ]);
    if ($validate->error) {
        Respond::json($validate);
    } else {
        Account::create([
            'name' => $request->input('name'),
            'email' => $request->input('email')
        ]);
    }
});

Route::get('/home', [View::class, 'home']);

Respond::status(404)::text('404 Not Found');
