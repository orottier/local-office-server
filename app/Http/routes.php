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
use App\Jobs\WriteMacAddresses;
use App\User;
use App\MacAddress;

$app->get('/', function () {
    return view('spa');
});


/*
 * TODO remove debug routes
 ****************************************************************/
$app->get('/api/users', function () {
    return User::with('macAddresses')->get();
});
$app->get('/api/flush', function () {
    dispatch(new WriteMacAddresses(storage_path('users.list')));
});
$app->get('/api/jobs', function () {
    return DB::table('jobs')->get();
});
/*****************************************************************
 * TODO remove debug routes
 */


$app->get('/api/status', function (Request $request) {
    $loggedIn = false;
    $user = Auth::user();
    if ($user) {
        $loggedIn = $user->username;
    }
    return [
        'status' => 'amazeballs',
        'loggedIn' => $loggedIn,
    ];
});

$app->post('/api/request-token', function (Request $request) {
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

$app->group([
    'prefix' => 'api',
    'middleware' => 'auth',
], function () use ($app) {

    $app->get('/users/{id}', function ($id) {
        if ($id === 'me') {
            $user = Auth::user();
        } else {
            $user = User::findOrFail($id);
            $this->authorize('view', $user);
        }
        return $user;
    });

    $app->get('/users/{id}/mac-addresses', function ($id) {
        if ($id === 'me') {
            $user = Auth::user();
        } else {
            $user = User::findOrFail($id);
            $this->authorize('view', $user);
        }
        return $user->macAddresses;
    });
    $app->post('/users/{id}/mac-addresses', function ($id, Request $request) {
        if ($id === 'me') {
            $user = Auth::user();
        } else {
            $user = User::findOrFail($id);
            $this->authorize('view', $user);
        }
        $address = new MacAddress(['mac_address' => $request->input('address')]);
        $this->authorize('create', $address);
        $user->macAddresses()->save($address);
        return $address;
    });
    $app->delete('/mac-addresses/{id}', function ($id) {
        $address = MacAddress::findOrFail($id);
        $this->authorize('delete', $address);
        $address->delete();
        return ['result' => 'success'];
    });

});
