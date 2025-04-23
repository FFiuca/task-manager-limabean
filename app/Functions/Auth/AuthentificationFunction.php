<?php
namespace App\Functions\Auth;

use App\Http\Requests\RegistrationForm;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthentificationFunction{
    public function register(array $data): User{
        DB::beginTransaction();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        DB::commit();

        return $user;
    }
}
