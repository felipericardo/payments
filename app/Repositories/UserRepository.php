<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class UserRepository extends Repository
{
    public function getUserById(int $userId)
    {
        $user = DB::selectOne("SELECT * FROM users WHERE id = :id", ['id' => $userId]);
        if (!empty($user)) {
            $user = $this->hydrateEntity('User', $user);
        }

        return $user;
    }

    public function decreaseBalance(int $userId, float $value)
    {
        return DB::update(
            "UPDATE users SET balance = balance - :value, updated_at = NOW() WHERE id = :id",
            [
                'id'    => $userId,
                'value' => $value,
            ]
        );
    }

    public function increaseBalance(int $userId, float $value)
    {
        return DB::update(
            "UPDATE users SET balance = balance + :value, updated_at = NOW() WHERE id = :id",
            [
                'id'    => $userId,
                'value' => $value,
            ]
        );
    }
}
