<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return $validator->getMessageBag();
        }

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
        ]);

        return [
            'token' => $user->createToken('api_token')->plainTextToken
        ];
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails() || !Auth::attempt($request->toArray())) {
            return response()->json(['error' => 'invalid_credentials'])->setStatusCode(401);
        }

        return response()->json(['api_token' => $request->user()->createToken('API Token')->plainTextToken]);
    }

    public function logout()
    {
        $user = request()->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return [
            'message' => 'tokens_removed'
        ];
    }
}
