<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:3'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ]);
        }

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        $token = $user->createToken('token_login')->plainTextToken;
        $user['token'] = $token;

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful',
            'data' => $user
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->all())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wrong username or password'
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('token_login')->plainTextToken;
        $user['token'] = $token;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => $user
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout successful'
        ]);
    }
}
