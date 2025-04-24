<?php
namespace App\Functions\Auth;

use App\Http\Requests\RegistrationForm;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

    public function login(array $data): array{
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new \Exception('Invalid credentials');
        }

        $token = $user->createToken(Str::random())->plainTextToken;
        return [
            'token' => $token,
            // 'type_token' => 'Bearer',
        ];
    }


}
