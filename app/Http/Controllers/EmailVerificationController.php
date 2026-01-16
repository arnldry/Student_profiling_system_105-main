<?php

namespace App\Http\Controllers;

use App\Models\PendingUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Notifications\EmailVerificationNotification;

class EmailVerificationController extends Controller
{
    /**
     * Clean up expired verification tokens
     */
    private function cleanExpiredTokens()
    {
        $expiryMinutes = config('auth.email_verification_expiry', 5);
        $expiredTime = Carbon::now()->subMinutes($expiryMinutes);

        // Clean expired pending users
        PendingUser::where('email_verification_sent_at', '<=', $expiredTime)
            ->delete();

        // Also clean expired tokens in users table (for safety)
        User::whereNotNull('email_verification_token')
            ->where('email_verification_sent_at', '<=', $expiredTime)
            ->where('is_email_verified', false)
            ->update([
                'email_verification_token' => null,
                'email_verification_sent_at' => null,
            ]);
    }

    /**
     * Show the email verification form
     */
    public function show(Request $request)
    {
        // Clean expired tokens first
        $this->cleanExpiredTokens();

        $token = $request->get('token');
        
        if (!$token) {
            return redirect()->route('register')
                ->with('error', 'Invalid verification link.');
        }

        $pendingUser = PendingUser::where('email_verification_token', $token)->first();
        
        if (!$pendingUser) {
            return redirect()->route('register')
                ->with('error', 'Invalid or expired verification link.');
        }

        // Check if token is expired (configurable minutes)
        $expiryMinutes = config('auth.email_verification_expiry', 5);
        if ($pendingUser->email_verification_sent_at && 
            Carbon::parse($pendingUser->email_verification_sent_at)->addMinutes($expiryMinutes)->isPast()) {
            
            // Clean up this expired pending user
            $pendingUser->delete();

            return redirect()->route('register')
                ->with('error', "Verification link has expired (valid for {$expiryMinutes} minutes only). Please register again.");
        }

        return view('auth.email-verification', compact('pendingUser', 'token'));
    }

    /**
     * Handle email verification and password creation
     */
    public function verify(Request $request)
    {
        // Clean expired tokens first
        $this->cleanExpiredTokens();

        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'password' => [
                'required',
                'confirmed',
                function ($attribute, $value, $fail) {
                    if (strlen($value) < 8) {
                        $fail('Password must be at least 8 characters long.');
                    } elseif (!preg_match('/[A-Z]/', $value) || !preg_match('/[a-z]/', $value)) {
                        $fail('Password must contain both uppercase and lowercase letters.');
                    } elseif (!preg_match('/[0-9]/', $value)) {
                        $fail('Password must include at least one number.');
                    } elseif (!preg_match('/[\W_]/', $value)) {
                        $fail('Password must include at least one special character.');
                    }
                },
            ],
        ], [
            'password.confirmed' => 'Passwords do not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pendingUser = PendingUser::where('email_verification_token', $request->token)->first();
        
        if (!$pendingUser) {
            return redirect()->route('register')
                ->with('error', 'Invalid verification link.');
        }

        // Check if token is expired (configurable minutes)
        $expiryMinutes = config('auth.email_verification_expiry', 5);
        if ($pendingUser->email_verification_sent_at && 
            Carbon::parse($pendingUser->email_verification_sent_at)->addMinutes($expiryMinutes)->isPast()) {
            
            // Clean up this expired pending user
            $pendingUser->delete();

            return redirect()->route('register')
                ->with('error', "Verification link has expired (valid for {$expiryMinutes} minutes only). Please register again.");
        }

        // Check if email already exists in users table (safety check)
        if (User::where('email', $pendingUser->email)->exists()) {
            $pendingUser->delete();
            return redirect()->route('register')
                ->with('error', 'This email is already registered. Please use a different email.');
        }

        // Create the actual user in users table
        $user = User::create([
            'name' => $pendingUser->name,
            'email' => $pendingUser->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'is_email_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Delete the pending user record
        $pendingUser->delete();

        return redirect()->route('login')
            ->with('success', 'Email verified successfully! You can now log in with your credentials.');
    }

    /**
     * Resend verification email
     */
    public function resend(Request $request)
    {
        // Clean expired tokens first
        $this->cleanExpiredTokens();

        $request->validate([
            'email' => 'required|email|exists:pending_users,email'
        ], [
            'email.exists' => 'No pending registration found for this email.'
        ]);

        $pendingUser = PendingUser::where('email', $request->email)->first();
        
        if (!$pendingUser) {
            return redirect()->route('register')
                ->with('error', 'No pending registration found for this email.');
        }

        // Generate new token
        $token = Str::random(60);
        $pendingUser->update([
            'email_verification_token' => $token,
            'email_verification_sent_at' => now(),
        ]);

        // Send verification email
        $pendingUser->notify(new EmailVerificationNotification($token));

        return redirect()->route('register')
            ->with('success', 'Verification email sent successfully! Please check your inbox.');
    }
}