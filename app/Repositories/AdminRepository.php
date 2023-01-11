<?php

namespace App\Repositories;

use App\Contracts\AdminInterface;
use App\Models\Admin;

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
        return Admin::create($data);
    }

    public function update($id, array $data)
    {
    }

    public function delete($id)
    {
    }
}
