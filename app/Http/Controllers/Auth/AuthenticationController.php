<?php

namespace App\Http\Controllers\Auth;

use App\Forms\LoginForm;
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
     *         description="Successful login",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="Test User"),
     *                 @OA\Property(property="email", type="string", format="email", example="test@gmail.com"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-24T23:09:21.000000Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-24T23:09:21.000000Z"),
     *                 @OA\Property(property="id", type="integer", example=2)
     *             ),
     *             @OA\Property(property="message", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="Name is required")),
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="Email is required"))
     *             ),
     *             @OA\Property(property="message", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="data", type="null", example=null),
     *             @OA\Property(property="message", type="string", example="some error")
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
            $register = $this->mainHelper->buildResponse(null, 500, message: $e->getMessage());
        }

        return response()->json($register, $register['status']);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Login a user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"email", "password"},
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
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", example="1|GOaH7bCcMHRsqxRkI9yclaCRX9biKs4ANl20C6QS7a39ad00")
     *             ),
     *             @OA\Property(property="message", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="email is required")),
     *                 @OA\Property(property="password", type="array", @OA\Items(type="string", example="password is required"))
     *             ),
     *             @OA\Property(property="message", type="string", nullable=true, example=null)
     *         )
     *     ),
     *    @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="data", type="null", example=null),
     *             @OA\Property(property="message", type="string", example="some error")
     *         )
     *     )
     * )
     */
    public function login(Request $request): JsonResponse {
        $login = null;
        try{
            $data = $request->post();

            $validated = LoginForm::login($data);
            if($validated->fails())
                throw new ValidationException($validated);

            $login =$this->authentificationFunction->login($data);

            $login = $this->mainHelper->buildResponse($login, 200, );
        }catch(ValidationException $e){
            $login = $this->mainHelper->buildResponse(null, 400, $e->errors());
        }catch(Exception $e){
            $login = $this->mainHelper->buildResponse(null, 500,  $e->getMessage());
        }

        return response()->json($login, $login['status']);
    }


}
