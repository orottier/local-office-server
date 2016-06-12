<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;

class UserController extends Controller
{
    public function view($id)
    {
        if ($id === 'me') {
            $user = Auth::user();
        } else {
            $user = User::findOrFail($id);
            $this->authorize('read', $user);
        }
        return $user;
    }
}
