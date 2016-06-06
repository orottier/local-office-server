<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\User;

use App\Jobs\SendSlackMessage;

class APIController extends Controller
{
    public function status()
    {
        $loggedIn = false;
        $user = Auth::user();
        if ($user) {
            $loggedIn = $user->username;
        }
        return [
            'status' => 'amazeballs',
            'loggedIn' => $loggedIn,
        ];
    }

    public function requestToken(Request $request) {
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
    }
}
