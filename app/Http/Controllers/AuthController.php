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
class AuthController extends Controller
{
    
    public function send_otp(Request $request)
    {
        // dd($request);
        $request->validate([
            'send_otp' => 'required|string',
        ]);

        // Generate a random OTP
        $otp = rand(100000, 999999);

        // Save OTP to the database
        OTP::create([
            'phone_number' => $request->phone_number,
            'otp' => $otp,
        ]);

        // You can send the OTP to the user via SMS or any other method here
        //send sms
        $templateID = "1407170608246834529";
        //MIPUI-AW atana I OTP chu {#var#} a ni e. EGOVMZ
        $message = "MIPUI-AW atana I OTP chu ".$otp ." a ni e. EGOVMZ";
        // $templateID = "1407165911820847483";
        // $message = "OTP for MIPUI-AW is ".$OTP .". -MSeGS";
       
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
        // dd($request);
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
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|string',
    //     ]);

    //     if (!Auth::attempt($request->only('email', 'password'))) {
    //         return response()->json(['message' => 'Unauthorized'], 401);
    //     }

    //     $user = User::where('email', $request->email)->firstOrFail();
    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json(['token' => $token], 200);
    // }
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
                        $token = $user->createToken($tokenName)->plainTextToken;

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

       
       
}
