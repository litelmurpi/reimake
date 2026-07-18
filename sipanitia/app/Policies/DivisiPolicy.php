<?php

namespace App\Policies;

use App\Models\Divisi;
use App\Models\User;

class DivisiPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Divisi $divisi): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Divisi $divisi): bool
    {
        return false;
    }

    public function delete(User $user, Divisi $divisi): bool
    {
        return false;
    }
}
