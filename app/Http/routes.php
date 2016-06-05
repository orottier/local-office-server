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

    $app->get('/me', function () {
        $user = Auth::user();
        $return = $user->toArray();
        $return['mac_addresses'] = $user->macAddresses->pluck('mac_address');
        return $return;
    });

    // TODO needs more REST
    $app->post('/me/addresses', function (Request $request) {
        $addresses = collect($request->input('addresses'));
        $user = Auth::user();
        $delete = $user->macAddresses->filter(function ($item) use ($addresses) {
            return !in_array($item->mac_address, $addresses->all());
        });
        $add = $addresses->filter(function ($item) use ($user) {
            return !in_array($item, $user->macAddresses->pluck('mac_address')->all());
        });
        foreach($delete as $mac) {
            $mac->delete();
        }
        foreach($add as $mac) {
            $user->macAddresses()->save(new MacAddress(['mac_address' => $mac]));
        }

        dispatch(new WriteMacAddresses(storage_path('users.list')));

        return [
            'add' => $add,
            'delete' => $delete,
        ];
    });

});
