<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Requests\User\TokenUserRequest;

class AuthController extends Controller
{
    /**
     * Register new user
     * 
     * @param RegisterUserRequest $request
     * @return JsonResponse
     */
    public function register(RegisterUserRequest $request)
    { 
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $token = $user->createToken('test')->plainTextToken;
  
        return response()->json(['token' => $token], 200);
    }

    /**
     * Create new token
     * 
     * @param TokenUserRequest $request
     * @return JsonResponse
     */
    public function token(TokenUserRequest $request)
    {   
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
        }
    
        return response()->json(['token' => $user->createToken('test')->plainTextToken]);
    }
}