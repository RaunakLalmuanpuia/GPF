<?php

namespace App\Http\Controllers;

use App\Models\OTP;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
        ]);

        // Generate a random OTP
        $otp = rand(100000, 999999);

        // Save OTP to the database
        OTP::create([
            'phone_number' => $request->phone_number,
            'otp' => $otp,
        ]);

        // You can send the OTP to the user via SMS or any other method here

        return response()->json(['message' => 'OTP sent successfully'], 200);
    }
    public function verifyOtpAndLogin(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'otp' => 'required|string',
        ]);

        // Find the OTP entry for the provided phone number and OTP
        $otp = Otp::where('phone_number', $request->phone_number)
            ->where('otp', $request->otp)
            ->where('expired', false)
            ->first();
            // return response()->json($otp, 200);

        if (!$otp) {
            return response()->json(['message' => 'Invalid OTP'], 401);
        }

        // Mark the OTP as expired
        $otp->update(['expired' => true]);

        // Find or create the user based on the phone number
        $user = User::firstOrNew(['phone_number' => $request->phone_number]);
        if (!$user->exists) {
            // User does not exist, you can create the user here if needed
            // Example: $user->name = 'New User';
            //          $user->save();
            return response()->json(['message' => 'User not found'], 404);
        }

        // Generate and return the token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }
    // public function logout(Request $request)
    // {
    //     $request->user()->currentAccessToken()->delete();

    //     return response()->json(['message' => 'Logged out']);
    // }
    public function user(Request $request)
    {
        // return $request->user();
        $user = $request->user();
        $user->load('roles'); // Load roles relationship
        return response()->json($user);
    }
    public function check(Request $request)
    {
    // dd($request);
     $credentials = $request->validate([
     'email' => ['required', 'email'],
     'password' => ['required'],
        ]);
        
        if (Auth::attempt($credentials)) 
        {
           return response()->json([ 'status' => true ,
                                     'message' => "Success"
        ]);
        }
            return response()->json(['status' => false ,
                                     'message' => "Fail"
        
        ]);
       }

       public function logout(Request $request)
        {
            Auth::logout();
            return response()->json(['message' => 'Successfully logged out']);
        }
}
