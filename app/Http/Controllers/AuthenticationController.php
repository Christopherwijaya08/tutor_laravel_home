<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use App\Models\UserSession;
use App\Models\UsersSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Create a new user session record
        UsersSession::create([
            'user_id' => $user->id,
            'login_time' => $request->loginDateTime,
        ]);
        $token = $user->createToken('login_token')->plainTextToken;
        return response()->json([
            'message' => 'Successfully Login',
            'token' => $token
        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required|min:6',
            'username' => 'required',
            'birthDate' => 'required'
        ]);

        // dd($request->username);
        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'birthDate' => $request->birthDate
        ]);


        return response()->json(['message' => 'Success Registered'], 200);
    }

    public function logout(Request $request)
    {
        $request->validate([
            'logout_time' => 'required'
        ]);

        // Retrieve the currently authenticated user
        $user = $request->user();

        // Find the user's latest session and update the logout time
        $userSession = UsersSession::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($userSession) {
            $userSession->update([
                'logout_time' => $request->logout_time,
            ]);
        }

        // Delete the current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function getUserDetailByToken(Request $request)
    {
       return new UserResource(Auth::user());
    }
}
