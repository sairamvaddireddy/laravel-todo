<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class RegisterController extends Controller
{
     /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return response()->json([
            'status' => false,
            'errors' =>$validator->errors(),
            'message' => 'Validation Error.'
            ], 404);
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        
        $userData['id'] = $user->id;
        $userData['name'] = $user->name;
        $userData['email'] = $user->email;
        

        return response()->json([
            'token' => $user->createToken('auth-token')->plainTextToken,
            'status' => true,
            'user' => $userData,
            'message' => 'User register successfully.'
        ]);
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (!auth()->attempt(['email' => $request->post('email'), 'password' =>$request->post('password')])) {
            return response()->json([
                'message' => 'invalid data',
                'errors' => [
                    'email' => [
                        'invalid credentials'
                    ]
                ]
            ]);
        }

        return response()->json([
            'token' => auth()->user()->createToken('auth-token')->plainTextToken,
            'status' => true,
            'message' => 'Login Successful'
        ]);
    }
    public function logout()
    {
        // dd(auth()->user());
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Logout Successful'
        ]);
    }
}
