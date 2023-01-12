<?php

namespace App\Repositories;

use App\Contracts\AdminInterface;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminRepository implements AdminInterface
{
    public function all()
    {
    }

    public function get($id)
    {
    }

    public function store(array $data)
    {
        return Admin::create([
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
