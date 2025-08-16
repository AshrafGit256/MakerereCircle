<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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

    public function auth_register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required|min:6',
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => $validator->errors()->first()
        ]);
    }
        $checkEmail = User::checkEmail($request->email);  // Corrected method
        if (empty($checkEmail)) {
            $save = new User;
            $save->name = trim($request->name);
            $save->username = trim($request->username);
            $save->email = trim($request->email);  // You should also store the email
            $save->title = trim($request->title);
            $save->password = Hash::make($request->password);
            $save->save();

            try {
                Mail::to($save->email)->send(new RegisterMail($save));
            } catch (\Exception $e) {
            }


            $user_id = 1;
            $url = url('admin/customer/list');
            $message = "New Customer Registers #" . $request->name;


            $json['status'] = true;
            $json['message'] = "Your account has been successfully registered. Please verify your email address";
        } else {
            $json['status'] = false;
            $json['message'] = "Email already registered, please use another one";
        }

        echo json_encode($json);
    }
}
