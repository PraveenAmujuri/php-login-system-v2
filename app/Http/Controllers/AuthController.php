<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\AuditLog;


class AuthController extends Controller
{
    public function showLogin() {
        // check for remember me cookie
        $token = request()->cookie('remember_token');
        // if token exists and session doesn't have user_id, try to log in
        if ($token && !session()->has('user_id')) {
            $user = User::where('remember_token', $token)->first();

            if ($user) {
                session(['user_id' => $user->id]);
                return redirect('/dashboard');
            }
        }

        return view('login');
    }

    public function showRegister() {
        return view('signup');
    }

    public function login(Request $request) {

        // validate input
        $request->validate([
            'userId' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        // find user by email
        $user = User::where('userId', $request->userId)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid credentials');
        }

        // check if account is inactive (FIRST LOGIN ATTEMPT)
        if ($user->status === 'inactive' && !$request->has('reactivate')) {

            // check if more than 30 days passed
            if ($user->deactivated_at) {
                $days = now()->diffInDays($user->deactivated_at);

                if ($days > 30) {
                    return back()->with('error', 'Account permanently deleted after 30 days.');
                }
            }

            // STOP login and ask confirmation popup
            return back()->with('confirm_reactivate', true)->withInput();
        }

        // login user (only after confirmation OR normal user)
        session(['user_id' => $user->id]);
        $user->last_login = now();

        // remember me functionality
        if ($request->has('remember')) {
            $token = Str::random(60);
            $user->remember_token = $token;

            Cookie::queue('remember_token', $token, 60 * 24 * 30);
        }

        // if user confirmed → reactivate account
        if ($user->status === 'inactive' && $request->has('reactivate')) {
            $user->status = 'active';
            $user->deactivated_at = null;
        }

        $user->save();

        //  login activity
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'login',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);

        return redirect('/dashboard')->with('success', 'Login successful');
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'userId' => 'required|email:rfc,dns|unique:users,userId',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name ?? explode('@', $request->userId)[0],
            'userId' => $request->userId,
            'password' => Hash::make($request->password),
            'status' => 'active',
            'provider' => 'local', 
            'email_verified_at' => now() 
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'register',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);

        return redirect('/');
    }

    public function deactivate(Request $request)
    {
        $user = User::find(session('user_id'));

        if (!$user) {
            return redirect('/');
        }

        // pdate account status
        $user->status = 'inactive';
        $user->deactivated_at = now();

        // remove remember token
        $user->remember_token = null;

        $user->save();

        // log action
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'deactivate',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);

        // clear session
        session()->flush();

        // delete cookie
        Cookie::queue(Cookie::forget('remember_token'));

        return redirect('/')->with('error', 'Account deactivated. You can login within 30 days.');
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
        $user = User::find($userId);

        if ($user) {
            $user->remember_token = null;
            $user->save();
        }

        // delete cookie
        Cookie::queue(Cookie::forget('remember_token'));

        session()->flush();
        return redirect('/');
    }
}