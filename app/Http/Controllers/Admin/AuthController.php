<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginAdminRequest;
use App\Http\Requests\StoreAdminRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginAdminRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            return $this->error('', 'Credentials do not match', 401);
        }

        $admin = Auth::guard('admin')->user();

        return $this->success([
            'user' => $admin,
            'token' => $admin->createToken('API Token of ' . $admin->name,['admin'])->plainTextToken
        ],'Successfully Login');
    }

    public function register(StoreAdminRequest $request)
    {
        $request->validated($request->all());

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return $this->success([
            'user' => $admin,
            'token' => $admin->createToken('API Token of '. $admin->name,['admin'])->plainTextToken
        ],'Successfully Register');
    }

    public function getMe()
    {
        $admin = Auth::user();
        return $this->success([
            'user' => $admin,
        ],'Admin Account Detail');
    }

    public function logout()
    {
        $admin = Auth::user();
        $admin->currentAccessToken()->delete();
        return $this->success(null,'Successfully Logout');
    }
}
