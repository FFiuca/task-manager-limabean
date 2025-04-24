<?php

namespace App\Forms;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LoginForm{

    protected static $rules = [
        'email' => ['required', 'string', 'email', 'max:255'],
        'password' => ['required', 'string', 'min:8'],
    ];

    static function login($data){
        return Validator::make($data, static::$rules);
    }
}

