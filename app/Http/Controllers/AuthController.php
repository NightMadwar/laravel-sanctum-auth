<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
{
    // Validate the request
    $validatedData = $request->validate([
        'User_Name' => 'required|string|max:255|unique:users',
        'Password' => 'required|string|min:6',
        'Email' => 'nullable|string|email|max:255|unique:users',
        'Gender' => 'nullable|in:male,female',
        'Age' => 'nullable|date',
        'Height' => 'nullable|numeric',
        'Weight' => 'nullable|numeric',
        'User_Location' =>'nullable|string'
    ]);

    // Create the user
    try {
        $user = User::create([
            'User_Name' => $validatedData['User_Name'],
            'Password' => Hash::make($validatedData['Password']),
            'Email' => $validatedData['Email'] ?? null,
            'Gender' => $validatedData['Gender']?? null,
            'Age' => $validatedData['Age'] ?? null,
            'Height' => $validatedData['Height'] ?? null,
            'Weight' => $validatedData['Weight'] ?? null,
            'User_Location' => $validatedData['User_Location'] ?? null,
        ]);

        $token = explode('|', $user->createToken('auth_token')->plainTextToken)[1];

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ],200);

    } catch (\Exception $e) {
        // Handle error
        return response()->json(['error' => 'Registration failed. Please try again.'], 500);
    }
}
    public function login(Request $request)
    {
        // dd($request);
        if (Auth::attempt($request->only('User_Name', 'Password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('User_Name', $request->User_Name)->firstOrFail();
        $token = explode('|', $user->createToken('auth_token')->plainTextToken)[1];
        

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
