<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationForm;
use Illuminate\Http\Request;

/**
    * @OA\Info(
    *     title="Task Manager API",
    *     version="1.0.0",
    *     description="API documentation for the Task Manager application"
    * )
    */
class AuthenticationController extends Controller
{

     /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *        @OA\JsonContent(ref="D:/test/limabean/task-manager/dto/schemas/json/authentication/registrationForm.json")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful registration",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User registered successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Validation error message.")
     *         )
     *     )
     * )
     */
    public function register(RegistrationForm $request){
        $validated = $request->validated();

        return true;
    }
}
