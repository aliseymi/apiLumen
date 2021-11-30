<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\User as UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validated = $this->validate($request,[
            'email' => 'required|string|email|exists:users',
            'password' => 'required|string'
        ]);

        $user = User::whereEmail($validated['email'])->first();

        if(! Hash::check($validated['password'],$user->password)){
            return response([
                'data' => 'طلاعات وارد شده معتبر نیست',
                'status' => 'error'
            ],403);
        }

        $user->update([
            'api_token' => Str::random(100)
        ]);

        return new UserResource($user);
    }

    public function register(Request $request)
    {
        $validated = $this->validate($request,[
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'api_token' => Str::random(100),
        ]);

        return new UserResource($user);
    }
}
