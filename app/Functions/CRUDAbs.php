<?php
namespace App\Functions;

abstract class CRUDAbs{
    abstract public function add(array $data): array|int|string;
    abstract public function update(int $id, array $data): array|int|string;
    abstract public function delete(int $id): array|int|string;
    abstract public function detail(int $id): array|int|string;
    abstract public function get(array $param): array|int|string;
}
