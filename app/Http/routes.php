<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Http\Request;

$app->get('/', function () use ($app) {
    return view('spa');
});

$app->post('/api/request-token', function (Request $request) use ($app) {
    return [
        'username' => $request->input('username'),
        'status' => 'success',
    ];
});
