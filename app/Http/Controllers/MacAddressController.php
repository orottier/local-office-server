<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\MacAddress;

use App\Jobs\WriteMacAddresses;

class MacAddressController extends Controller
{
    public function indexForUser($id)
    {
        if ($id === 'me') {
            $user = Auth::user();
        } else {
            $user = User::findOrFail($id);
            $this->authorize('read', $user);
        }
        return $user->macAddresses;
    }

    public function createForUser($id, Request $request)
    {
        if ($id === 'me') {
            $user = Auth::user();
        } else {
            $user = User::findOrFail($id);
            $this->authorize('read', $user);
        }
        $address = new MacAddress(['mac_address' => $request->input('address')]);
        $this->authorize('create', $address);
        $user->macAddresses()->save($address);

        $this->dispatch(new WriteMacAddresses(storage_path('users.list')));

        return $address;
    }

    public function delete($id)
    {
        $address = MacAddress::findOrFail($id);
        $this->authorize('delete', $address);
        $address->delete();

        $this->dispatch(new WriteMacAddresses(storage_path('users.list')));

        return ['result' => 'success'];
    }
}
