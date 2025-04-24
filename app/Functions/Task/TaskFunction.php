<?php

namespace App\Functions\Task;

use App\Functions\CRUDAbs;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskFunction extends CRUDAbs{

    public function add(array $data): array|int|string
    {
        DB::beginTransaction();

        $task = Task::create([
            ...$data,
            'created_by' => request()->user()->id,
        ]);

        // dump($task);
        DB::commit();
        return $task->toArray();
    }

    public function update(int $id, array $data): array|int|string
    {
        DB::beginTransaction();

        $task = Task::find($id);
        if (!$task) {
            throw new \Exception('Task not found');
        }

        $update = Task::where('id', $id)->update($data);
        // dump($task);
        DB::commit();
        return $task->refresh()->toArray();
    }

    public function delete(int $id): array|int|string
    {
        DB::beginTransaction();

        $task = Task::find($id);
        if (!$task) {
            throw new \Exception('Task not found');
        }

        $task->deleted_by = request()->user()->id;
        $task->deleted_at = now();
        $task->save();

        DB::commit();
        return true;
    }
    public function detail(int $id): array|int|string
    {
        $task = Task::where('id', $id)->with([
            'epic',
            'reportTo',
            'assignTo',
            'status',
            'deletedBy',
            'taskComment' => fn($q) => $q->with([
                'createdBy',
            ]),
            'createdBy',
        ])->first();
        if (!$task) {
            throw new \Exception('Task not found');
        }

        return $task->toArray();
    }

    public function get(array $param): array|int|string
    {
        $data = Task::with([
            'createdBy',
        ])->paginate(
            page: $param['page'] ?? 1,
            perPage: $param['per_page'] ?? 10,
        );


        return [
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'data' => $data->items(), // Only the actual data items
        ];
    }

}
