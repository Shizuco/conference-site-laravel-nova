<?php
declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $fields = $request->validated();
        $fields['password'] = bcrypt($request->password);
        $user = User::create($fields);

        $token = $user->createToken('mytasktoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function login(LoginRequest $request)
    {
        $fields = $request->validated();

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Bad creds',
            ], 401);
        }

        
        $token = $user->createToken('mytasktoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response()->json($response, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->currentAccessToken()->delete();

        return [
            'message' => 'logout',
        ];
    }
}
