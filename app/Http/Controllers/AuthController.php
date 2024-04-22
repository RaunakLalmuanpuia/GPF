<?php

namespace App\Http\Controllers;

use App\Models\OTP;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use App\Models\IndividualInfo;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
    public function send_otp(Request $request)
    {
        // dd($request);
        $request->validate([
            'send_otp' => 'required|string',
            'phone_number' =>'required'
        ]);

        // Generate a random OTP
        $otp = rand(100000, 999999);

        // Query the database to check if the phone number exists
        $exists = IndividualInfo::where('phone', $request->phone_number)->exists();
        
        if (!$exists) {
            return response()->json(['message' => 'Phone number does not exist'], 404);
        } 
        // Save OTP to the database
        OTP::create([
            'phone_number' => $request->phone_number,
            'otp' => $otp,
        ]);

        // You can send the OTP to the user via SMS or any other method here
        //send sms
        $templateID = "1407170608246834529";
        $message = "MIPUI-AW atana I OTP chu ".$otp ." a ni e. EGOVMZ";
        Http::withHeaders([
            'Authorization' => "Bearer 551|" . env('SMS_TOKEN'),
         ])->get("https://sms.msegs.in/api/send-otp",[
            'template_id' => $templateID,
            'message' => $message,
            'recipient'=>$request->phone_number
         ]);
        
        return response()->json(['message' => 'OTP sent successfully'], 200);
    }

    public function verifyOtpAndSearch(Request $request)
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

        // Find or Individual info and the entry info the user based on the phone number
        $individualInfos = IndividualInfo::where('phone', $request->phone_number)
        // Eager load the relations with EntryInfo and Template models
        ->with('entryInfo.signatory', 'template')
        ->get();

        return response()->json([
            'individualInfos' => $individualInfos
        ],200);
        
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
    
    public function user(Request $request)
    {
        // dd($request->user());
        // return $request->user();
        $user = $request->user();
        // $user->load('roles'); // Load roles relationship
        return response()->json(['user' => $user]);
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
    public function login(Request $request): JsonResponse
    {
                $credentials = $request->validate([
                    'email' => ['required', 'email'],
                    'password' => ['required','min:8'],
                ]);

                try {

                    $user = User::where('email',$credentials['email'])->first();

                    if(!$user || !Hash::check($credentials['password'],$user->password)){

                        return response()->json(['message' => 'Invalid Credentials'], 401);
                    }

                    if (Auth::attempt($credentials)) {

                        $tokenName = 'Token'.rand(111,999);
                        // $token = $user->createToken($tokenName)->plainTextToken;
                        $token = $user->createToken($tokenName, ['expires_in' => 30 * 60])->plainTextToken;


                        return response()->json([
                                'status' => 200,
                                'message' => 'Login Successful',
                                'access_token' => $token,
                                'token_type' => 'Bearer',
                            ], 200);
                    }else{

                        return response()->json(['message' => 'Invalid credentials'], 401);
                    }

                } catch (\Throwable $th) {

                    return response()->json([
                        'message' => 'Something went wrong'.$th->getMessage(),
                        'status' => 500
                    ], 500);
                }
    }

    public function logout()
    {
            // dd( User::findOrFail(Auth::id()));
            $user = User::findOrFail(Auth::id());
            $user->tokens()->delete();
            

            return response()->json([
                'status' => 200,
                'message' => 'Logged out successfully'
            ], 200);
    }
    
    // public function logout()
    //     {
    //         try {
    //             // Get the user's user_id
    //             $userId = Auth::id();
                
    //             // Find all users with the same user_id
    //             $users = User::where('user_id', $userId)->get();
                
    //             // Loop through each user and delete their tokens
    //             foreach ($users as $user) {
    //                 $user->tokens()->delete();
    //             }

    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'Logged out successfully for all users with the same user_id'
    //             ], 200);
    //         } catch (\Throwable $th) {
    //             return response()->json([
    //                 'status' => 500,
    //                 'message' => 'Error logging out: ' . $th->getMessage()
    //             ], 500);
    //         }
    //     }

    public function update(Request $request)
        {
            // Define validation rules
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:users,id',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $request->id,
                'password' => 'nullable|string|min:6',
            ]);

            // If validation fails, return error response
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            try {
                // Retrieve the user by ID
                $user = User::findOrFail($request->id);

                // Update the user's profile
                $user->name = $request->name;
                $user->email = $request->email;
               
                if (!is_null($request->password)) {
                    $user->password = bcrypt($request->password); // Hash the password for security
                }

                // Save the updated user
                $user->save();

                // Return success response
                return response()->json(['message' => 'User profile updated successfully'], 200);
            } catch (\Exception $e) {
                // Return error response if an exception occurs
                return response()->json(['message' => 'Failed to update user profile', 'error' => $e->getMessage()], 500);
            }
        }
}
