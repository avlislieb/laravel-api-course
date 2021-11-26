<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\{
    JWTException,
    TokenExpiredException,
    TokenInvalidException
};
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthApiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => ['authenticate']
        ]);
    }


    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            $token = JWTAuth::attempt($credentials);
            if (!$token) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $user = auth()->user();
        // all good so return the token
        return response()->json(compact(['token', 'user']));
    }

    public function getAuthenticatedUser()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], 400);

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], 400);

        } catch (JWTException $e) {

            return response()->json(['token_absent'], 400);

        }

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }

    public function refreshToken()
    {
        $token = JWTAuth::getToken();
        if (!$token) {
            return response()->json(['token_not_send'], 401);
        }
        try {
            $token = JWTAuth::refresh();
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], 400);
        }
        return response()->json(compact('token'));
    }
}
