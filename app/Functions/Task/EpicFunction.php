<?php

namespace App\Functions\Task;

use App\Functions\CRUDAbs;
use App\Models\Epic;
use Illuminate\Support\Facades\DB;

class EpicFunction extends CRUDAbs{

    public function add(array $data): array|int|string
    {
        DB::beginTransaction();

        $epic = Epic::create([
            ...$data,
            'created_by' => request()->user()->id,
        ]);

        // dump($epic);
        DB::commit();
        return $epic->toArray();
    }

    public function update(int $id, array $data): array|int|string
    {
        DB::beginTransaction();

        $epic = Epic::find($id);
        if (!$epic) {
            throw new \Exception('Epic not found');
        }

        $epic->title = $data['title'];
        $epic->save();
        // dump($epic);
        DB::commit();
        return $epic->toArray();
    }

    public function delete(int $id): array|int|string
    {
        DB::beginTransaction();

        $epic = Epic::find($id);
        if (!$epic) {
            throw new \Exception('Epic not found');
        }

        $epic->delete();

        DB::commit();
        return true;
    }
    public function detail(int $id): array|int|string
    {
        $epic = Epic::where('id', $id)->with([
            'tasks' => fn($query) =>$query->with([
                'createdBy',
                'status',
                'assignTo',
                'reportTo',
            ]),
            'createdBy',
        ])->first();
        if (!$epic) {
            throw new \Exception('Epic not found');
        }

        return $epic->toArray();
    }

    public function get(array $param): array|int|string
    {
        $data = Epic::with([
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
