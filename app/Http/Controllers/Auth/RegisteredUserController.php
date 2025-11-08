<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PendingUser;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $this->checkDatabase();

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkDatabase();

        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÑñ\s\-\']+$/u'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÑñ\s\-\']+$/u'],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-zÑñ\s\-\']*$/u'],
            'suffix' => ['nullable', 'string', 'max:50', 'regex:/^[A-Za-zÑñ\s\-\']*$/u'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                'unique:pending_users,email',
                'regex:/^[\w.%+-]+@(gmail|yahoo)\.com$/i',
            ],
        ], [
            'email.regex' => 'You must register with a valid Gmail or Yahoo address.',
            'email.unique' => 'This email is already registered or pending verification.',
            'first_name.required' => 'First name is required.',
            'first_name.regex' => 'First name can only contain letters (including Ñ/ñ), spaces, hyphens, and apostrophes.',
            'last_name.required' => 'Last name is required.',
            'last_name.regex' => 'Last name can only contain letters (including Ñ/ñ), spaces, hyphens, and apostrophes.',
            'middle_name.regex' => 'Middle name can only contain letters (including Ñ/ñ), spaces, hyphens, and apostrophes.',
            'suffix.regex' => 'Suffix can only contain letters (including Ñ/ñ), spaces, hyphens, and apostrophes.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Combine all name fields into one full name
        $fullName = trim("{$request->first_name} {$request->middle_name} {$request->last_name} {$request->suffix}");

        // Generate verification token
        $verificationToken = Str::random(60);

        // Store in pending_users table instead of users table
        PendingUser::create([
            'name' => $fullName,
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'suffix' => $request->suffix,
            'email_verification_token' => $verificationToken,
            'email_verification_sent_at' => now(),
        ]);

        // Send verification email
        $pendingUser = PendingUser::where('email', $request->email)->first();
        $pendingUser->notify(new EmailVerificationNotification($verificationToken));

        return redirect()->route('register')
            ->with('success', 'Registration successful! Please check your email for verification link to create your password.');
    }

    /**
     * Check if database and required tables exist, otherwise throw 404.
     */
    protected function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            $tables = DB::select("SHOW TABLES");
            $tableNames = array_map('current', $tables);
            if (!in_array('users', $tableNames) || !in_array('pending_users', $tableNames)) {
                throw new NotFoundHttpException('Database or required tables not found.');
            }
        } catch (\Throwable $e) {
            throw new NotFoundHttpException('Database not found.');
        }
    }
}