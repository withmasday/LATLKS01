<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class AuthController extends BaseController
{
    public function signin(Request $request) {
        $validator = Validator::make($request->all(), [
            'username'=> 'required',
            'password'=> 'required',
            'confirm'=> 'required|same:password'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if(Auth::attempt(['username' => $request->username, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('Auth User')-> accessToken; 
            $success['username'] =  $user->username;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    public function signup(Request $request, User $user) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('Auth User')->accessToken;
        $success['name'] =  $user->name;
        return $this->sendResponse($success, 'Register Success!');
   
    }

    public function signout(Request $request) {
        if ($request->user()) {
            $request->user()->tokens()->delete();
            return $this->sendResponse('LogOut Successfully', null);
        }
        return $this->sendError('You Must LoggedIn First!', null);
    }
}
