<?php

namespace App\Repositories;

use App\Contracts\UserInterface;
use App\Models\User;

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
        return User::create($data);
    }

    public function update($id, array $data)
    {
    }

    public function delete($id)
    {
    }
}
