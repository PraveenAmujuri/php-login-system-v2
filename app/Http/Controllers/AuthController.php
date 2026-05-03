<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\AuditLog;

class AuthController extends Controller
{
    public function showLogin() {
        return view('login');
    }

    public function showRegister() {
        return view('signup');
    }

    public function login(Request $request) {
        $request->validate([
            'userId' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);
        $user = User::where('userId', $request->userId)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid credentials');
        }

        session(['user_id' => $user->id]);

        // update last login
        $user->last_login = now();
        $user->save();
        // log the login action
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'login',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);
        return redirect('/dashboard');
        }

        public function register(Request $request)
        {
            $request->validate([
                'userId' => 'required|email:rfc,dns',
                'password' => 'required|min:6'
            ]);

            //  manual duplicate check
            if (User::where('userId', $request->userId)->exists()) {
                return back()->with('error', 'User already exists');
            }
            // create user
            $user = User::create([
                'userId' => $request->userId,
                'password' => Hash::make($request->password),
                'status' => 'active'
            ]);
            // log the registration action
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'register',
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent')
            ]);

            return redirect('/');
        }

    public function dashboard() {
        if (!session('user_id')) {
            return redirect('/');
        }
        // log the dashboard access
        $user = User::find(session('user_id'));
        $logs = AuditLog::where('user_id', $user->id)->get();

        return view('dashboard', compact('user', 'logs'));
    }

    public function logout() {
        $userId = session('user_id');

        AuditLog::create([
            'user_id' => $userId,
            'action' => 'logout',
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent')
        ]);

        session()->flush();
        return redirect('/');
    }
}