<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderModel;
use App\Models\NotificationModel;
use App\Models\User;
use App\Models\ProductReviewModel;
use App\Models\ProductWishlistModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function dashboard()
    {
        $data['meta_title'] = 'Dashboard';
        $data['meta_description'] = '';
        $data['meta_keywords'] = '';

       

        return view('user.dashboard', $data);
    }


    public function edit_profile()
    {
        $data['meta_title'] = 'Edit Profile';
        $data['meta_description'] = '';
        $data['meta_keywords'] = '';
        $data['getRecord'] = User::getSingle(Auth::user()->id);

        return view('user.edit_profile', $data);
    }

    public function update_profile(Request $request)
    {
        $user = User::getSingle(Auth::user()->id);
        $user->name = trim($request->first_name);
        $user->last_name = trim($request->last_name);
        $user->company_name = trim($request->company_name);
        $user->country = trim($request->country);
        $user->address_one = trim($request->address_one);
        $user->address_two = trim($request->address_two);
        $user->city = trim($request->city);
        $user->state = trim($request->state);
        $user->postcode = trim($request->postcode);
        $user->phone = trim($request->phone);
        $user->save();

        return redirect()->back()->with('success', "Profile successfully updated");
    }

 
    public function change_password()
    {
        $data['meta_title'] = 'Change Password';
        $data['meta_description'] = '';
        $data['meta_keywords'] = '';

        return view('user.change_password', $data);
    }

    public function update_password(Request $request)
    {
        $user = User::getSingle(Auth::user()->id);
        if (Hash::check($request->old_password, $user->password)) {

            if ($request->password == $request->cpassword) {
                $user->password = Hash::make($request->password);
                $user->save();

                return redirect()->back()->with('success', "Password successfully updated");
            } else {
                return redirect()->back()->with('error', "New password and confirm password don't match");
            }
        } else {
            return redirect()->back()->with('error', "Old password was not found");
        }
    }

    
}
