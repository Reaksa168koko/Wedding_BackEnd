<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Carbon\Carbon;
use Exception;

class AuthController extends Controller
{
    // ================= REGISTER =================
  public function register(Request $request)
{
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

          $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'user' => $user,
            'token' => $token
        ], 201);    

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Registration failed',
            'error' => $e->getMessage()
        ], 500);
    }
}


    // ================= LOGIN =================
  public function login(Request $request)
{
    try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }

        // Delete old OTP if exists
        Otp::where('user_id', $user->id)->delete();

        // Generate new OTP
        $otpCode = rand(100000, 999999);

        Otp::create([
            'user_id' => $user->id,
            'otp' => $otpCode,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Send email
        Mail::to($user->email)->send(new SendOtpMail($otpCode));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to your email.'
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Login failed.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    // ================= VERIFY OTP =================
  public function verifyOtp(Request $request)
{
    try {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        $otp = Otp::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.'
            ], 400);
        }

       
        $otp->delete();

    
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'user' => $user,
            'token' => $token
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'OTP verification failed.',
            'error' => $e->getMessage()
        ], 500);
    }
}


    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resend OTP to user email
     */
    public function resendOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ], 404);
            }

            if (!is_null($user->email_verified_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already verified.'
                ], 400);
            }


            Otp::where('user_id', $user->id)->delete();

            $otpCode = rand(100000, 999999);

            Otp::create([
                'user_id' => $user->id,
                'otp' => $otpCode,
                'expires_at' => now()->addMinutes(5)
            ]);


            Mail::to($user->email)->send(new SendOtpMail($otpCode));

            return response()->json([
                'success' => true,
                'message' => 'New OTP sent to your email.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Resend OTP failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
