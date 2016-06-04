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
use Illuminate\Support\Facades\Crypt;

$app->get('/', function () use ($app) {
    return view('spa');
});

$app->get('/api/status', function (Request $request) use ($app) {
    $auth = $request->header('X-Authorization');
    $loggedIn = false;
    if ($auth && strpos($auth, 'Bearer ') === 0) {
        $loggedIn = Crypt::decrypt(substr($auth, 7));
    }
    return [
        'status' => 'amazeballs',
        'loggedIn' => $loggedIn,
    ];
});

$app->post('/api/request-token', function (Request $request) use ($app) {
    $username = $request->input('username');
    $token = Crypt::encrypt($username);
    $success = dispatch(new SendSlackMessage('@' . $username, 'here is your token: `' . $token . '`'));
    return [
        'username' => $username,
        'status' => $success ? 'success' : 'error',
    ];
});
