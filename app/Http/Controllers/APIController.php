<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\User;

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
            'logged_in' => $loggedIn,
        ];
    }

    public function requestToken(Request $request) {
        $username = strtolower($request->input('username'));
        if (!$username) {
            abort(400);
        }

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
