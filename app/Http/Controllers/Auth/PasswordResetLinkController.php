<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        // Check database availability
        $this->checkDatabase();

        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Check database availability
        $this->checkDatabase();

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }

    /**
     * Check if database and 'users' table exist, otherwise throw 404.
     */
    protected function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            $tables = DB::select("SHOW TABLES");
            $tableNames = array_map('current', $tables);
            if (!in_array('users', $tableNames)) {
                throw new NotFoundHttpException('Database or users table not found.');
            }
        } catch (\Throwable $e) {
            throw new NotFoundHttpException('Database not found.');
        }
    }
}
