<?php

namespace App\Policies;

use App\User;

class UserPolicy
{
    public function create(User $actor, User $subject)
    {
        return true;
    }

    public function read(User $actor, User $subject)
    {
        return true;
    }

    public function update(User $actor, User $subject)
    {
        return $subject->id == $actor->id;
    }

    public function delete(User $actor, User $subject)
    {
        return $this->update($actor, $address);
    }
}
