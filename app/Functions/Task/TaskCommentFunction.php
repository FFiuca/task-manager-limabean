<?php

namespace App\Functions\Task;

use App\Functions\CRUDAbs;
use App\Models\Epic;
use App\Models\TaskComment;
use Illuminate\Support\Facades\DB;

class TaskCommentFunction{

    public function add(array $data): array|int|string
    {
        DB::beginTransaction();

        $epic = TaskComment::create([
            ...$data,
            'created_by' => request()->user()->id,
        ]);

        DB::commit();
        return $epic->toArray();
    }

    public function update(int $id, array $data): array|int|string
    {
        DB::beginTransaction();

        $epic = TaskComment::find($id);
        if (!$epic) {
            throw new \Exception(message: 'Task comment not found');
        }

        $epic->comment = $data['comment'];
        $epic->save();

        DB::commit();
        return $epic->toArray();
    }

    public function delete(int $id): array|int|string
    {
        DB::beginTransaction();

        $epic = TaskComment::find($id);
        if (!$epic) {
            throw new \Exception(message: 'Task comment not found');
        }

        $epic->delete();

        DB::commit();
        return true;
    }

}
