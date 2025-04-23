<?php

namespace App\Helper;

class MainHelper{

    public function buildResponse($data, int $status, $message=null): array{
        return [
            'status' => $status,
            'data' => $data,
            'message' => $message,
        ];
    }
}
