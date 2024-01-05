<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Log;

class UserController extends Controller
{
    public $successStatus = 200;

    public function Login(Request $request) {

        $credentials = $request->only('email', 'password');

        //valid credentials
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //if request is not valid , send failed response
        if($validator->fails()){
            return response()->json(['error' => $validator->messages()], $this->successStatus);
        }

        //if Request is Validated
        //Create Token
        try {
            if(! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are Invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            // return $credentials;
            return response()->json([
                'success' => false,
                'message' => 'Could not create token.'
            ],500);
        }

        //if Token Created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    public function booking(Request $request) {
        Log::error($request);
        return 1;
    }
}
