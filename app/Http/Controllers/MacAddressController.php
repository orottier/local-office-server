<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\User;
use App\MacAddress;

class MacAddressController extends Controller
{
    public function indexForUser($id) {
        if ($id === 'me') {
            $user = Auth::user();
        } else {
            $user = User::findOrFail($id);
            $this->authorize('view', $user);
        }
        return $user->macAddresses;
    }

    public function createForUser($id, Request $request) {
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
    }

    public function delete($id) {
        $address = MacAddress::findOrFail($id);
        $this->authorize('delete', $address);
        $address->delete();
        return ['result' => 'success'];
    }
}
