<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\MacAddress;

class UserController extends Controller
{
    public function view($id) {
        if ($id === 'me') {
            $user = Auth::user();
        } else {
            $user = User::findOrFail($id);
            $this->authorize('view', $user);
        }
        return $user;
    }
}
