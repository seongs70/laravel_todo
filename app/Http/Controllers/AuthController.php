<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $successStatus  =   200;

    //----------------- [ Register user ] -------------------
    public function registerUser(Request $request) {

        $validator  =   Validator::make($request->all(),
            [
                'name'              =>      'required|min:3',
                'email'             =>      'required|email',
                'password'          =>      'required|alpha_num|min:5',
                'confirm_password'  =>      'required|same:password'
            ]
        );

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input              =       array(
            'name'          =>          $request->name,
            'email'         =>          $request->email,
            'password'      =>          bcrypt($request->password),
            'address'       =>          $request->address,
            'city'          =>          $request->city
        );

        // check if email already registered
        $user                   =       User::where('email', $request->email)->first();
        if(!is_null($user)) {
            $data['message']     =      "Sorry! this email is already registered";
            return response()->json(['success' => false, 'status' => 'failed', 'data' => $data]);
        }

        // create and return data
        $user                   =       User::create($input);
        $success['message']     =       "You have registered successfully";

        return response()->json( [ 'success' => true, 'user' => $user ] );
    }

    // -------------- [ User Login ] ------------------

    public function userLogin(Request $request) {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])) {

            // getting auth user after auth login
            $user = Auth::user();

            $token                  =       $user->createToken('token')->accessToken;
            $success['success']     =       true;
            $success['message']     =       "Success! you are logged in successfully";
//            $success['token']       =       $token;

            return response()->json(['success' => $success,'access_token' => $token], $this->successStatus);
        }

        else {
            return response()->json(['error'=>'Invalid Request. Please enter a username or a password.'], 401);
        }
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json('Logged out successfully',200);
    }
}
