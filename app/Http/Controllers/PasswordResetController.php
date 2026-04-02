<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
    // 1. Nampilin form masukin email
    public function requestForm() {
        return view('forgot-password');
    }

    // 2. Proses ngirim link reset ke email
    public function sendResetLink(Request $request) {
        $request->validate(['email' => 'required|email']);
        
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['success' => 'Link reset password sudah dikirim ke email kamu!'])
                    : back()->withErrors(['email' => 'Email tidak ditemukan di sistem kami.']);
    }

    // 3. Nampilin form bikin password baru (pas link di email diklik)
    public function resetForm(Request $request, $token) {
        return view('reset-password', ['token' => $token, 'email' => $request->email]);
    }

    // 4. Proses nyimpen password baru ke database
    public function updatePassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed', // Harus ada input password_confirmation
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect('/login')->with('success', 'Asyik! Password berhasil direset! Silakan login pakai password baru.')
                    : back()->withErrors(['email' => 'Gagal mereset password. Token tidak valid.']);
    }
}