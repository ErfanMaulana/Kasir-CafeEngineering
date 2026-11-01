<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    /**
     * Tampilkan halaman form login admin.
     */
    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Proses autentikasi login admin.
     *
     * Mendukung input 'username' maupun 'email'.
     * Validasi keamanan password: harus mengandung minimal 1 huruf besar dan 1 angka/simbol.
     */
    public function login(Request $request): RedirectResponse
    {
        $loginInput = $request->input('username', $request->input('email'));

        // Dukung username default 'EngineringAdminCafe' atau alamat email resmi
        $email = ($loginInput === 'EngineringAdminCafe')
            ? 'admin@engineeringcafe.unsil.ac.id'
            : $loginInput;

        $request->merge([
            'username' => $loginInput,
            'email' => $email,
        ]);

        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string', 'regex:/^(?=.*[A-Z])(?=.*[\d\W]).+$/'],
        ], [
            'username.required' => 'Username atau E-mail wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar dan 1 angka atau simbol.',
        ]);

        $credentials = [
            'email' => $email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat Datang Kembali, Admin!');
        }

        return back()->withErrors([
            'username' => 'Username/E-mail atau Password salah. Pastikan password memenuhi kriteria keamanan.',
        ])->withInput($request->only('username', 'email'));
    }

    /**
     * Proses logout admin dan bersihkan session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
