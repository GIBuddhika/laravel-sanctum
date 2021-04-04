<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{

    public function get(Request $request, int $id)
    {
        $user = User::where('id',$id)->first();

        return [
            'user' => $user
        ];
    }
}
