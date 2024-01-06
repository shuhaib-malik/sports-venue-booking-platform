<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Log;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login(Request $request) {

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

        $user_details = User::where('email',$request->email)->get();

        $user = new User;
        $user->user_id = $user_details[0]->user_id;
        $user->user_name = $user_details[0]->name;
        $user->email = $user_details[0]->email;
        $user->mobile = $user_details[0]->phone_number;
        $user->token = $token;

        return response()->json([
            'success' => true,
            'user_details' => $user
        ]);
    }

    public function booking(Request $request) {
        Log::error($request);
        return 1;
    }
}
