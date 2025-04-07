<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\NotificationModel;
use App\Mail\RegisterMail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Admin login page
    public function login_admin()
    {
        if (!empty(Auth::check()) && Auth::user()->is_admin == 1) {
            return redirect('admin/dashboard');
        }

        return view('admin.auth.login');
    }

    // Handle admin login
    public function auth_login_admin(Request $request)
    {
        $remember = !empty($request->remember) ? true : false;

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'is_admin' => 1,
            'status' => 0,
            'is_delete' => 0
        ], $remember)) {
            return redirect('admin/dashboard');
        } else {
            return redirect()->back()->with('error', "Please enter correct email and password");
        }
    }

    // Logout admin
    public function logout_admin()
    {
        Auth::logout();
        return redirect('admin');
    }

    // Customer registration
    public function auth_login(Request $request)
    {
        $remember = !empty($request->is_remember) ? true : false;

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'status' => 0,
            'is_delete' => 0
        ], $remember)) {
            if (!empty(Auth::user()->email_verified_at)) {
                $json['status'] = true;
                $json['message'] = 'success';
            } else {
                $save = User::getSingle(Auth::user()->id);

                Auth::logout();
                $json['status'] = false;
                $json['message'] = 'your account is not verified, Please check your inbox and verify';
            }
        } else {
            $json['status'] = false;
            $json['message'] = 'Please enter correct email and password';
        }

        echo json_encode($json);
    }
}
