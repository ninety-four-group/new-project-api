<?php

namespace App\Repositories;

use App\Contracts\UserInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserInterface
{
    public function all()
    {
    }

    public function get($id)
    {
    }

    public function store(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function update($id, array $data)
    {
    }

    public function delete($id)
    {
    }
}
