<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest as ApiRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Responses\ApiResponse;

class AuthController extends Controller
{
    public function register(ApiRegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully!',
            'user' => $user,
        ], 201);
    }

    // Login (Issue Token)
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            return new ApiResponse(401, errorMessage: 'The provided credentials are incorrect.');
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return new ApiResponse(200, data: [
            'message' => 'Login successful!',
            'token' => $token,
        ]);
    }

    // Logout (Revoke Token)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return new ApiResponse(200, data: [
            'message' => 'Logged out successfully!',
        ]);
    }
}
