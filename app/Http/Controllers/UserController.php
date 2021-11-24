<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index() {
    	return view('users.index');
    }

    public function store() {
        $full_name = request('full_name');
        $phone     = request('phone');
        $email     = request('email');
        $department_id     = request('department_id');
        $password = request('register_password_confirm');


        $check = User::where('email', $email)->count();

        if($check > 0) {
            return redirect()->back()->with('error', 'User ' . $email . ' exists');
        }

        $user = new User;
        $user->name = $full_name;
        $user->email = $email;
        $user->phone = $phone;
        $user->department_id = $department_id;
        $user->password = bcrypt($password);
        $user->role_id  = 2;

        $user->save();


        return redirect()->back()->with('success', 'Successfully saved');

    }

    public function activateAll() {
       $userids = request('userids');
       
       $emails = [];
       
       foreach($userids as $uid){
            $user = User::find($uid);
            $user->active = 1;
            $user->verified = 1;
            $user->save();
            $emails[] = $user->email;
       }

        // Send Email Here
        // try {
        //     $data = array('fullname'=>$user->name, "email"=>$user->email, "admin"=>\App\User::where('role_id', 1)->first()->email, "emails"=>$emails);

        //     \Mail::send('emails.verified_mail', $data, function($message) use ($data) {
        //         $message->to($data["emails"])
        //                 ->subject('ACCOUNT ACTIVATED');
        //         $message->from($data["admin"], 'SYSTEM ADMIN');
        //     });
        // }catch(Exception $e) {
        //     dd($e);
        // }

    }

    public function updatePassword($id) {
        $pass = request('editcpassword');
        $user = User::find($id);
        $user->password = bcrypt($pass);
        $user->save();
    }

    public function refresh() {
        return redirect()->back()->with('success', 'Successfully Updated');
    }

    public function activate() {
        $uid = request('userid');
        $user = User::find($uid);
        $user->active = 1;
        $user->save();
    }

    public function deactivate() {
        $uid = request('userid');
        $user = User::find($uid);
        $user->active = 0;
        $user->save();
    }

    public function update($id){
        $fullname = request('fullname');
        $email    = request('email');
        $phone     = request('phone');
        $department_id     = request('department_id');
        $check    = User::where('email', $email)->where('id', '!=', $id)->count();

        if($check){
            return response()->json([
                'error' => true,
                'msg'   => "User already existed"
            ]);
        }

        $user = User::find($id);
        $user->name = $fullname;
        $user->email = $email;
        $user->phone = $phone;
        $user->department_id = $department_id;
        $user->save();

        return response()->json([
            'error' => false,
            'msg'   => "Updated Successfully"
        ]);

    }

    public function edit($id) {
        return view('users.edit', compact('id'));
    }

    public function activateSingle() {
        $userid = request('userid');
        $user = User::find($userid);
        $user->active = 1;
        $user->verified = 1;
        $user->save();
        // Send Email Here
        // try {
        //     $data = array('fullname'=>$user->name, "email"=>$user->email, "admin"=>\App\User::where('role_id', 1)->first()->email);
    
        //     \Mail::send('emails.verified_mail', $data, function($message) use ($data) {
        //         $message->to($data["email"], $data["fullname"])
        //         //$message->to('joramkimata@gmail.com', 'SYSTEM ADMIN')
        //                 ->subject('ACCOUNT ACTIVATED');
        //         $message->from($data["admin"], 'SYSTEM ADMIN');
        //     });
        // }catch(Exception $e) {
        //     dd($e);
        // }
    }

    public function activateAllRefresh() {
        return redirect()->back();
    }
}
