<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['username', 'name', 'email', 'password'];
    protected $useTimestamps = true;

    public function getUserByUsername(string $username)
    {
        return $this
            ->where('username', $username)
            ->first();
    }

    public function getUserByEmail(string $email)
    {
        return $this
            ->where('email', $email)
            ->first();
    }

    public function isUsernameExists(string $username): bool
    {
        return $this->where('username', $username)->countAllResults() > 0;
    }

    public function isEmailExists(string $email): bool
    {
        return $this->where('email', $email)->countAllResults() > 0;
    }
}
