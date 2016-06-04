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
use App\Jobs\SendSlackMessage;
use App\User;

$app->get('/', function () use ($app) {
    return view('spa');
});

$app->get('/api/users', function () use ($app) {
    return User::all();
});

$app->get('/api/status', function (Request $request) use ($app) {
    $auth = $request->header('X-Authorization');
    $loggedIn = false;
    if ($auth && strpos($auth, 'Bearer ') === 0) {
        $token = substr($auth, 7);
        $user = User::where('token', $token)->first();
        if ($user) {
            $loggedIn = $user->username;
        }
    }
    return [
        'status' => 'amazeballs',
        'loggedIn' => $loggedIn,
    ];
});

$app->post('/api/request-token', function (Request $request) use ($app) {
    $username = $request->input('username');
    $token = substr(md5(rand()), 0, 12);

    $user = User::firstOrNew([
        'username' => $username,
    ]);
    $user->token = $token;
    $user->save();

    $success = dispatch(new SendSlackMessage('@' . $username, 'here is your token: `' . $token . '`'));
    return [
        'username' => $username,
        'status' => $success ? 'success' : 'error',
    ];
});
