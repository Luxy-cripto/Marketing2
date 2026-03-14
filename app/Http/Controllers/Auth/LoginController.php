<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function redirectTo()
    {
        $user = auth()->user();

        if (!$user) {
            return '/login';
        }

        if ($user->role === 'admin') {
            return '/admin/dashboard';
        }

        if ($user->role === 'marketing') {
            return '/marketing/dashboard';
        }

        return '/home';
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
