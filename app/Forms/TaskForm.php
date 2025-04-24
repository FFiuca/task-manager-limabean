<?php

namespace App\Forms;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskForm{

    protected static $rules = [
        'title' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'assign_user_id' => ['required', 'integer'],
        'priority' => ['nullable',],
        'due_date' => ['nullable', 'date'],
        'status_id' => ['required', 'integer'],
        'epic_id' => ['required', 'integer'],
        'report_to_user_id'=> ['nullable', 'integer'],

    ];

    static function add($data){
        return Validator::make($data, [
            ...static::$rules,
            'priority' => [
                ...static::$rules['priority'],
                Rule::in(['low', 'medium', 'high']),
            ]
        ]);
    }

    static function update($data){
        return Validator::make($data, [
            ...static::$rules,
            'priority' => [
                ...static::$rules['priority'],
                Rule::in(['low', 'medium', 'high']),
            ]
        ]);
    }
}

