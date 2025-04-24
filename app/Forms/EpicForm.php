<?php

namespace App\Forms;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EpicForm{

    protected static $rules = [
        'title' => ['required', 'string', 'max:255'],
    ];

    static function add($data){
        return Validator::make($data, static::$rules);
    }

    static function update($data){
        return Validator::make($data, static::$rules);
    }
}

