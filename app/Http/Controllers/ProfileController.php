<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('profile');
    }

    public function name_change(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        User::find(Auth::id())->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'name changed');;
    }

    public function change_password(Request $request)
    {
        $request->validate([
            '*'            => 'required',
            'new_password' => 'min:8'
        ]);

        $hashcheck = Hash::check($request->old_password, Auth::user()->password);

        if ($hashcheck) {
            if ($request->new_password == $request->confirm_password) {
                User::find(Auth::id())->update([
                    'password' => Hash::make($request->new_password),
                ]);
                return redirect()->back()->with('success', 'password changed');
            } else {
                return back()->withErrors('password match donot match');
            }
        } else {
            return back()->withErrors('old password donot match');
        }
    }

    public function profile_photo_change(Request $request)
    {
        $request->validate([
            'new_profile_photo' => 'required | image',
        ]);

        if (Auth::user()->profile_photo !== 'default.jpg') {
            $old_link = base_path('public/uploads/profile_photos/' . Auth::user()->profile_photo);
            unlink($old_link);
        }

        $ext = $request->file('new_profile_photo')->getClientOriginalExtension();
        $new_name = Auth::id() . '-' . uniqid() . '.' . $ext;
        Image::make($request->file('new_profile_photo'))->resize(300, 300)->save(base_path('public/uploads/profile_photos/' . $new_name));

        User::find(Auth::id())->update([
            'profile_photo' => $new_name,
        ]);

        return back()->with('success', "photo uploaded");
    }
}
