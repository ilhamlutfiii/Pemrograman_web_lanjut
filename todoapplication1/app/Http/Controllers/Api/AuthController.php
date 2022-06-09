<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->apiSuccess([
            'token'=> $token,
            'token_type'=> 'Bearer',
            'user'=> $user
        ]);
    }

    public function login(LoginRequest $request)
    {
        $validated=$request->validated();
        if(!Auth::attempt($validated)){
            return $this->apiError('Credentials not match', Response::HTTP_UNAUTHORIZED);
        }
        $user= User::where('email', $validated['email'])->first();
        $token= $user->createToken('auth_token')->plainTextToken;

        return $this->apiSuccess([
            'token'=>$token,
            'token_type'=>'Bearer',
            'user'=>$user
        ]);
    }

    public function logout()
    {
        try{
            auth()->user()->tokens()->delete();
            return $this->apiSuccess('Token revoked');
        } catch (\Throwable $e){
            throw new HttpResponseException($this->apiError(
                null,
                Response::HTTP_INTERNAL_SERVER_ERROR,
            ));
        }
    }
}
