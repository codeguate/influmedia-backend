<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Users;
use Response;
use Validator;
use Auth;

class AuthenticateController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username'  => 'required',
            'password'  => 'required'
        ]);

        if ( $validator->fails() ) {
            $returnData = array (
                'status' => 400,
                'message' => 'Invalid Parameters',
                'validator' => $validator
            );
            return Response::json($returnData, 400);
        }
        else {
            try {
                $validator = Validator::make($request->all(), [
                    'username'  => 'email',
                ]);
                $userdata = array();
                if ( $validator->fails() ) {
                    $userdata = array(
                        'username'  => $request->get('username'),
                        'password'  => $request->get('password')
                    );
                }else{
                    // $field = (preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $request->get('username'), null)) ? 'email' : 'username';
                    $userdata = array(
                        'email'  => $request->get('username'),
                        'password'  => $request->get('password')
                    );
                }
                

                if(Auth::attempt($userdata, true)) {
                    $user = Users::find(Auth::user()->id);
                    $user->save();
                    return Response::json($user, 200);
                }
                else {
                    $returnData = array (
                        'status' => 401,
                        'message' => 'No valid Username or Password'
                    );
                    return Response::json($returnData, 401);
                }


            } catch (Exception $e) {
                $returnData = array (
                    'status' => 500,
                    'message' => $e->getMessage()
                );
                return Response::json($returnData, 500);
            }
        }
    }

    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar'      => 'required|image|mimes:jpeg,png,jpg',
            'carpeta'      => 'required'
        ]);
    
        if ($validator->fails()) {
            $returnData = array(
                'status' => 400,
                'message' => 'Invalid Parameters',
                'validator' => $validator->messages()->toJson()
            );
            return Response::json($returnData, 400);
        }
        else {
            try {
    
                $path = Storage::disk('s3')->put($request->carpeta, $request->avatar);
                return Response::json(Storage::disk('s3')->url($path), 200);
    
            }
            catch (Exception $e) {
                $returnData = array(
                    'status' => 500,
                    'message' => $e->getMessage()
                );
            }
    
        }
    }
}
