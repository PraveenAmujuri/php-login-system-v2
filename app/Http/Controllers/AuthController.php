<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() {
        return view('login');
    }

    public function showRegister() {
        return view('signup');
    }

    public function login(Request $request) {
        $user = User::where('userId', $request->userId)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid credentials');
        }

        session(['user_id' => $user->id]);

        // update last login
        $user->last_login = now();
        $user->save();

        return redirect('/dashboard');
    }

    public function register(Request $request) {
        User::create([
            'userId' => $request->userId,
            'password' => Hash::make($request->password)
        ]);

        return redirect('/');
    }

    public function dashboard() {
        if (!session('user_id')) {
            return redirect('/');
        }

        return view('dashboard');
    }

    public function logout() {
        session()->flush();
        return redirect('/');
    }
}