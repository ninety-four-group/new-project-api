<?php

namespace App\Http\Controllers;

use App\Contracts\UserInterface;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;

class AuthController extends Controller
{
    use HttpResponses;


    protected $user;

    public function __construct(UserInterface $interface){
        $this->user = $interface;
    }

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('', 'Credentials do not match', 401);
        }

        $user = Auth::user();

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->name,['user'])->plainTextToken
        ],'Successfully Login');
    }

    public function register(StoreUserRequest $request)
    {
        $request->validated($request->all());

        $user = $this->user->store($request->all());

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of '. $user->name,['user'])->plainTextToken
        ],'Successfully Register');
    }

    public function getMe()
    {
        $user = Auth::user();
        return $this->success([
            'user' => $user,
        ],'User Account Detail');
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return $this->success(null,'Successfully Logout');
    }

}
