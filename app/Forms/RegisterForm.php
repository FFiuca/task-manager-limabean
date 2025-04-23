<?php

namespace App\Forms;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterForm{

    protected static $rules = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8'],
        'confirm_password' => ['required', 'string', 'min:8'],
    ];

    static function register($data){
        return Validator::make($data, [
            ...self::$rules,
            'confirm_password' => [
                ...self::$rules['confirm_password'],
                Rule::prohibitedIf(fn() => $data['password'] !== $data['confirm_password'])
            ]
        ]);
    }
}

