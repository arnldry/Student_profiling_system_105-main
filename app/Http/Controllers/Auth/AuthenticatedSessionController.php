<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\AdditionalInformation;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // Check if database is available
        try {
            DB::connection()->getPdo();
            $tables = DB::select("SHOW TABLES");
            $tableNames = array_map('current', $tables);
            $dbMissing = !in_array('users', $tableNames);
        } catch (\Throwable $e) {
            $dbMissing = true;
        }

        // If database missing, use fallback superadmin
        if ($dbMissing) {
            return $this->handleFallbackSuperadmin($request);
        }

        // Normal login flow
        return $this->normalLoginFlow($request);
    }

    protected function handleFallbackSuperadmin(LoginRequest $request): RedirectResponse
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $fallbackEmail = env('FALLBACK_SUPERADMIN_EMAIL', 'superadmin@ocnhs.com');
        $fallbackPassword = env('FALLBACK_SUPERADMIN_PASSWORD', 'SuperOCNHS2025!');

        if ($email === $fallbackEmail && $password === $fallbackPassword) {
            // Fake superadmin user
            $user = new User();
            $user->id = 0;
            $user->name = 'Superadmin';
            $user->role = 'superadmin';
            $user->status = 'active';

            Auth::login($user);

            // Mark as static_admin
            session(['static_admin' => true]);

            // Redirect to recovery page instead of normal dashboard
            return redirect()->route('recovery.index')
                ->with('success', 'Fallback superadmin logged in. You may now upload a backup.');
        }

        // If fallback credentials don't match, show database error
        return back()->withErrors([
            'email' => 'Database connection failed. Please contact administrator.',
        ])->withInput();
    }

    protected function normalLoginFlow(LoginRequest $request): RedirectResponse
    {
        $request->merge(['email' => trim($request->email)]);
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $this->ensureIsNotRateLimited($request);

        // Check if user exists first
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey($request));
            return $this->handleInvalidCredentials($request);
        }

        if (!Auth::attempt($request->only('email','password'), $request->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey($request));
            return $this->handleInvalidCredentials($request);
        }

        RateLimiter::clear($this->throttleKey($request));
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->status !== 'active') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact administrator.'
            ])->withInput();
        }

        switch ($user->role) {
            case 'superadmin':
                return redirect()->intended(route('superadmin.dashboard'));
            case 'admin':
                return redirect()->intended(route('admin.dashboard'));
            case 'student':
                $hasAdditionalInfo = AdditionalInformation::where('learner_id', $user->id)->exists();
                if (!$hasAdditionalInfo) {
                    return redirect()->intended(route('student.additional-info'));
                }
                return redirect()->intended(route('student.dashboard'));
            default:
                return redirect()->route('landing');
        }
    }

    /**
     * Handle invalid email or password credentials
     */
    protected function handleInvalidCredentials(LoginRequest $request): RedirectResponse
    {
        return back()->withErrors([
            'invalid_credentials' => 'Invalid email or password. Please try again.'
        ])->withInput();
    }

    protected function ensureIsNotRateLimited(Request $request)
    {
        $key = $this->throttleKey($request);
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'email' => __('Too many login attempts. Please try again in :seconds seconds.', [
                    'seconds' => RateLimiter::availableIn($key)
                ]),
            ]);
        }
    }

    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('email')).'|'.$request->ip();
    }


}