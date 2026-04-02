<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // API BUAT REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default langsung jadi user biasa
        ]);

        // Bikinin Karcis (Token) buat Flutter
        $token = $user->createToken('OutfitologyMobileToken')->plainTextToken;

        // Balikin datanya dalam bentuk JSON
        return response()->json([
            'message' => 'Register Berhasil Cuy!',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // API BUAT LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        // Kalau email gak ketemu atau password salah, tendang!
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau Password salah cuy!'
            ], 401);
        }

        // Bikinin Karcis (Token) baru
        $token = $user->createToken('OutfitologyMobileToken')->plainTextToken;

        return response()->json([
            'message' => 'Login Berhasil!',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    // API BUAT LOGOUT
    public function logout(Request $request)
    {
        // Hapus karcisnya biar Flutter gak bisa akses lagi
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Berhasil Logout dan token dihapus!'
        ], 200);
    }

    // 🔥 FUNGSI BUAT NYIMPEN ALAMAT PERMANEN 🔥
    public function updateAlamat(Request $request)
    {
        $user = \App\Models\User::find(auth()->id());

        $user->update([
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'detail_alamat' => $request->detail_alamat,
        ]);

        return response()->json([
            'message' => 'Mantap, Alamat berhasil disimpan!',
            'user' => $user
        ], 200);
    }
}