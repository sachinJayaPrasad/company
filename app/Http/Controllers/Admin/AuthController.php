<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class AuthController extends Controller
{
    //Login Function
    public function login(Request $request)
    {
        try{
            $validation = Validator::make($request->all(),[
                'email'    => 'required',
                'password' => 'required',
            ]);
            if($validation->fails()){
                return redirect('/')->with(array('message' => 'Incorrect!', 'status' => 'success'));
            }
            if(auth()->guard('admin')->attempt(['email'=>$request->email,'password'=> $request->password])){
                $admin = Auth()->guard('admin')->user();
                return redirect('/dashboard');
            }else{
                return redirect('/')->with(array('message' => 'Invalid Credentails !', 'status' => 'success'));
            }
        }catch(\Exception $e){
            return response()->json(['status'=>0,'response'=>$e->getMessage()]);
        }
    }
    //Logout Function
    public function logout(Request $request) {
        Auth::logout();
        return redirect('/');
    }
}
