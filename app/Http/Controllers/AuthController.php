<?php
declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Plan;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        if ($request->password != $request->password_confirmation) {
            $error = ValidationException::withMessages([
                'password' => ['Password must be equal to password confirmation'],
            ]);
            throw $error;
        }
        if($request->role == 'admin')
        {
            abort(403, 'You can`t register admin');
        }
        $fields = $request->validated();
        $fields['password'] = bcrypt($request->password);
        $fields['left_joins'] = 1;
        $user = User::create($fields);

        $token = $user->createToken('mytasktoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];
        $plan = Plan::where('name', 'Basic')->firstOrFail();
        $subscription = $user->newSubscription('Basic', $plan->stripe_plan)
            ->create()->cancel();
        return response($response, 201);
    }

    public function login(LoginRequest $request)
    {
        if ($request->email === 'admin@groupbwt.com') {
            $error = ValidationException::withMessages([
                'email' => ['Access denied'],
            ]);
            throw $error;
        }
        $fields = $request->validated();

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $error = ValidationException::withMessages([
                'email' => ['No account with such email'],
            ]);
            throw $error;
        }
        if (!Hash::check($request->password, $user->password)) {
            $error = ValidationException::withMessages([
                'password' => ['Incorrect login or password'],
            ]);
            throw $error;
        }

        $token = $user->createToken('mytasktoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        if (count(Subscription::where('user_id', $user->id)->get()) === 0) {
            $plan = Plan::where('name', 'Basic')->firstOrFail();
            $subscription = $user->newSubscription('Basic', $plan->stripe_plan)
                ->create()->cancel();
        }

        return response()->json($response, 201);
    }

    public function logout(Request $request)
    {
        if (!auth()->user()->currentAccessToken()) {
            return response()->json('unauthenticate', 302);
        }
        auth()->user()->currentAccessToken()->delete();

        return [
            'message' => 'logout',
        ];
    }
}
