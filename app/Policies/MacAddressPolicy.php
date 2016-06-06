<?php

namespace App\Policies;

use App\User;
use App\MacAddress;

class MacAddressPolicy
{
    public function create(User $actor, MacAddress $address)
    {
        return true;
    }

    public function read(User $actor, MacAddress $address)
    {
        return $this->update($actor, $address);
    }

    public function update(User $actor, MacAddress $address)
    {
        return $address->user_id == $actor->id;
    }

    public function delete(User $actor, MacAddress $address)
    {
        return $this->update($actor, $address);
    }
}
