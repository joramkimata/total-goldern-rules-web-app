<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index() {
        return view('app.settings');
    }

    public function changeapplogo(){
        $s = \App\Setting::find(1);
        
        if (request()->file('change_logo')) {
            $s->logo =  \App\HelperX::uplodFileThenReturnPath('change_logo');
            $s->save();
        }
        return redirect()->back()->with('success', 'Successfully Updated!'); 
    }

    public function changeappname() {
        $appname = request('appname');
        $base_url = request('base_url');
        $s = \App\Setting::find(1);
        $s->system_name = $appname;
        $s->base_url = $base_url;
        $s->save();
        return redirect()->back()->with('success', 'Successfully Updated!'); 
    }

    public function changepassword(){
        $cnewPassword = request('cnewPassword');
        $user = User::find(auth()->user()->id);
        $user->password = bcrypt($cnewPassword);
        $user->save();
        return redirect()->back()->with('success', 'Successfully Updated!'); 
    }

    public function changeemail() {
        $email = request('email');
        $userid = auth()->user()->id;
        $check = User::where('email', $email)->where('id', '!=', $userid)->count();
        if($check) {
            return redirect()->back()->with('error', 'Email already used'); 
        }
        $user = User::find(auth()->user()->id);
        $user->email = $email;
        $user->save();
        return redirect()->back()->with('success', 'Successfully Updated!'); 
    }
}
