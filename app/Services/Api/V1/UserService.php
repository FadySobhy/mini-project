<?php


namespace App\Services\Api\V1;


use App\Services\Api\V1\Interfaces\AuthTypeInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService implements AuthTypeInterface
{

    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function customRules()
    {
        //add any custom rules for this user type
        return [

        ];
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
