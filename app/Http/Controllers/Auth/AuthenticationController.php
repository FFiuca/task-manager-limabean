<?php

namespace App\Http\Controllers\Auth;

use App\Forms\RegisterForm;
use App\Functions\Auth\AuthentificationFunction;
use App\Helper\MainHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationForm;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
    * @OA\Info(
    *     title="Task Manager API",
    *     version="1.0.0",
    *     description="API documentation for the Task Manager application"
    * )
    */
class AuthenticationController extends Controller
{

    public function __construct(
        private AuthentificationFunction $authentificationFunction,
        private MainHelper $mainHelper
    ){
        // $this->authentificationFunction = $authentificationFunction;
        // $this->mainHelper = $mainHelper;
    }

     /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="The name of the user",
     *                 example="John Doe"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 description="The email address of the user",
     *                 example="john.doe@example.com"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 description="The password for the user account",
     *                 example="password123"
     *             ),
     *             @OA\Property(
     *                 property="confirm_password",
     *                 type="string",
     *                 format="password",
     *                 description="Password confirmation",
     *                 example="password123"
     *             )
     *         )
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
    public function register(Request $request): JsonResponse {
        $register = null;
        try{
            $data = $request->post();

            $validated = RegisterForm::register($data);
            if($validated->fails())
                throw new ValidationException($validated);

            $register =$this->authentificationFunction->register($data);

            $register = $this->mainHelper->buildResponse($register, 200, );
        }catch(ValidationException $e){
            $register = $this->mainHelper->buildResponse(null, 400, $e->errors());
        }catch(Exception $e){
            $register = $this->mainHelper->buildResponse(null, 500, message: $e);
        }

        return response()->json($register, $register['status']);
    }
}
