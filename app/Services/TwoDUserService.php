<?php

namespace App\Services;

use App\Models\User;

class TwoDUserService
{
    public function getAllTwoDUsersWithAgents()
    {
        return User::with('agent')->get();
    }
}
