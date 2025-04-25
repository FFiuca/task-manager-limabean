<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Functions\Task\EpicFunction;
use App\Helper\MainHelper;
use App\Forms\EpicForm;
use Illuminate\Validation\ValidationException;

class EpicController extends Controller
{

    function __construct(
        private EpicFunction $function,
        private MainHelper $mainHelper
    ){}


    /**
     * @OA\Get(
     *     path="/api/epic",
     *     summary="Get a list of epics",
     *     tags={"Epic"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number to retrieve",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="The number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer", example=11),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="deleniti ut sunt"),
     *                         @OA\Property(
     *                             property="created_by",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Test User"),
     *                             @OA\Property(property="email", type="string", format="email", example="test@example.com"),
     *                             @OA\Property(property="email_verified_at", type="string", format="date-time", example="2025-04-25T12:29:54.000000Z"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-25T12:29:54.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-25T12:29:54.000000Z")
     *                         ),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-25T12:29:54.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-25T12:29:54.000000Z"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                     )
     *                 )
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
     *             @OA\Property(property="message", type="string", example="Validation error message.")
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
    public function index(Request $request): JsonResponse
    {
        $list = null;
        try{
           $list = $this->function->get($request->query());

           $list = $this->mainHelper->buildResponse($list, 200);
        }catch(Exception $e){
            $list = $this->mainHelper->buildResponse(null, 500,  $e->getMessage());
        }

        return response()->json($list, $list['status']);
    }

    /**
     * @OA\Post(
     *     path="/api/epic",
     *     summary="Create a new epic",
     *     tags={"Epic"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"title"},
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 description="The title of the epic",
     *                 example="New Epic"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful creation of an epic",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="New Epic"),
     *                 @OA\Property(property="created_by", type="integer", example=1),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-25T12:43:34.000000Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-25T12:43:34.000000Z"),
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
     *                 @OA\Property(property="title", type="array", @OA\Items(type="string", example="title is required"))
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
    public function store(Request $request)
    {
        $add = null;
        try{
            $data = $request->post();

            $validated = EpicForm::add($data);
            if($validated->fails())
                throw new ValidationException($validated);

            $add =$this->function->add($data);

            $add = $this->mainHelper->buildResponse($add, 200, );
        }catch(ValidationException $e){
            $add = $this->mainHelper->buildResponse(null, 400, $e->errors());
        }catch(Exception $e){
            $add = $this->mainHelper->buildResponse(null, 500,  $e->getMessage());
        }

        return response()->json($add, $add['status']);
    }

     /**
     * @OA\Put(
     *     path="/api/epic/{id}",
     *     summary="Update an existing epic",
     *     tags={"Epic"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the epic to update",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"title"},
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 description="The updated title of the epic",
     *                 example="Updated Epic Title"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful update of an epic",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="Updated Epic Title"),
     *                 @OA\Property(property="created_by", type="integer", example=1),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-25T12:43:34.000000Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-25T12:43:34.000000Z"),
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
     *                 @OA\Property(property="title", type="array", @OA\Items(type="string", example="title is required"))
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
    public function update(string $id, Request $request): JsonResponse
    {
        $update = null;
        try{
            $data = $request->post();

            $validated = EpicForm::update($data);
            if($validated->fails())
                throw new ValidationException($validated);

            $update =$this->function->update($id, $data);

            $update = $this->mainHelper->buildResponse($update, 200, );
        }catch(ValidationException $e){
            $update = $this->mainHelper->buildResponse(null, 400, $e->errors());
        }catch(Exception $e){
            $update = $this->mainHelper->buildResponse(null, 500,  $e->getMessage());
        }

        return response()->json($update, $update['status']);
    }

     /**
     * @OA\Get(
     *     path="/api/epic/{id}",
     *     summary="Get details of a specific epic",
     *     tags={"Epic"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the epic to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of epic details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Epic Title"),
     *                 @OA\Property(
     *                     property="created_by",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Test User"),
     *                     @OA\Property(property="email", type="string", format="email", example="test@example.com")
     *                 ),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-25T12:43:34.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-25T12:43:34.000000Z"),
     *                 @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *             ),
     *             @OA\Property(property="message", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Epic not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Epic not found")
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
    public function show(String $id)
    {
        $data = null;
        try{
           $data = $this->function->detail($id);

           $data = $this->mainHelper->buildResponse($data, 200);
        }catch(Exception $e){
            $data = $this->mainHelper->buildResponse(null, 500,  $e->getMessage());
        }

        return response()->json($data, $data['status']);
    }

    /**
     * @OA\Delete(
     *     path="/api/epic/{id}",
     *     summary="Delete a specific epic",
     *     tags={"Epic"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the epic to delete",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful deletion of the epic",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Epic not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Epic not found")
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
    public function destroy(string $id)
    {
        $destroy = null;
        try{
           $destroy = $this->function->delete($id);

           $destroy = $this->mainHelper->buildResponse($destroy, 200);
        }catch(Exception $e){
            $destroy = $this->mainHelper->buildResponse(null, 500,  $e->getMessage());
        }

        return response()->json($destroy, $destroy['status']);
    }
}
