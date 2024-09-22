<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UpdateUser extends Controller
{
    public function updateUser(int $userId, array $data)
    {
        $user = User::findOrFail($userId);

        if ($user->update($data)) {
            return $user;
        }else {
            return $user;
        }
    }
}
