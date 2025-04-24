<?php

namespace App\Forms;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskCommentForm{

    protected static $rules = [
        'task_id' => ['required', 'integer'],
        'comment' => ['required', 'string'],
    ];

    static function add($data){
        return Validator::make($data, static::$rules);
    }

    static function update($data){
        $rule = static::$rules;
        unset($rule['task_id']);

        return  Validator::make($data, $rule);
    }
}

