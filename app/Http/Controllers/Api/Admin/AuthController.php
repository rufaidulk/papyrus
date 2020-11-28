<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Api\ApiBaseController;

/**
 * Auth Controller
 */
class AuthController extends ApiBaseController
{
    public function login(Request $request)
    {
        $messages = ['exists' => 'User not found'];

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'exists:users'],
            'password' => ['required', 'string'],
        ], $messages);

        if ($validator->fails()) {
            return $this->error($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (! Auth::attempt($request->all())) {
            return $this->error('Wrong password', Response::HTTP_UNAUTHORIZED);
        }

        Auth::user()->tokens()->delete();
        $response = Auth::user()->loginResponseToApi();

        return $this->success(['data' => $response], 'Logged in successfully!', Response::HTTP_OK);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => User::STATUS_ACTIVE
        ]);
        
        $response = $user->loginResponseToApi();
    
        return $this->success(['data' => $response], 'Registered successfully!', Response::HTTP_CREATED);
    }
}