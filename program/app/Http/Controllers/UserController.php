<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request){
        $incomingFields = $request->validate([
            'name' => ['required', Rule::unique('users', 'name')], # validate that the name is required and unique in the users table
            'email' => ['required', Rule::unique('users', 'email')], 
            'password' => 'required'
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']); # encrypt the password before saving it to the database
        $user = User::create($incomingFields); 
        auth()->login($user); # log the user in after registration
        return redirect('/'); # redirect the user to the home page after registration
    }

    public function logout(){
        auth()->logout(); # log the user out
        return redirect('/'); # redirect the user to the home page after logging out
    }

    public function login(Request $request){ # ToDo here you'd do input validaiton
        $incomingFields = $request->validate([
            'loginname' => 'required',
            'loginpassword' => 'required'
        ]);

        if(auth()->attempt(['name' => $incomingFields['loginname'], 'password' => $incomingFields['loginpassword']])){ 
            $request->session()->regenerate(); # regenerate the session to prevent session fixation attacks
        } 
        return redirect('/'); 
        
    }
}
