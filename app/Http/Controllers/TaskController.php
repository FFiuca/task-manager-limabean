<?php

namespace App\Http\Controllers;

use App\Forms\TaskForm;
use App\Functions\Task\TaskFunction;
use App\Helper\MainHelper;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    function __construct(
        private TaskFunction $function,
        private MainHelper $mainHelper
    ){

    }

    /**
     * @OA\Get(
     *     path="/api/task",
     *     summary="Get a list of tasks",
     *     tags={"Task"},
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
     *                         @OA\Property(property="title", type="string", example="New Task"),
     *                         @OA\Property(property="description", type="string", example="This is a test task description."),
     *                         @OA\Property(property="status_id", type="integer", example=1),
     *                         @OA\Property(property="epic_id", type="integer", example=2),
     *                         @OA\Property(property="assign_user_id", type="integer", example=2),
     *                         @OA\Property(property="report_to_user_id", type="integer", example=2),
     *                         @OA\Property(property="priority", type="string", example="medium"),
     *                         @OA\Property(property="due_date", type="string", format="date-time", example="2025-05-02 13:12:11"),
     *                         @OA\Property(property="created_by", type="integer", example=2),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-25T13:12:11.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-25T13:12:11.000000Z")
     *                     )
     *                 )
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
     *     path="/api/task",
     *     summary="Create a new task",
     *     tags={"Task"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"title", "assign_user_id", "status_id", "epic_id"},
     *             @OA\Property(property="title", type="string", example="New Task"),
     *             @OA\Property(property="description", type="string", nullable=true, example="This is a test task description."),
     *             @OA\Property(property="assign_user_id", type="integer", example=1),
     *             @OA\Property(property="priority", type="string", nullable=true, example="medium"),
     *             @OA\Property(property="due_date", type="string", format="date-time", nullable=true, example="2025-05-02 13:12:11"),
     *             @OA\Property(property="status_id", type="integer", example=1),
     *             @OA\Property(property="epic_id", type="integer", example=1),
     *             @OA\Property(property="report_to_user_id", type="integer", nullable=true, example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful creation of a task",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="New Task"),
     *                 @OA\Property(property="description", type="string", example="This is a test task description."),
     *                 @OA\Property(property="status_id", type="integer", example=1),
     *                 @OA\Property(property="epic_id", type="integer", example=2),
     *                 @OA\Property(property="assign_user_id", type="integer", example=2),
     *                 @OA\Property(property="report_to_user_id", type="integer", example=2),
     *                 @OA\Property(property="priority", type="string", example="medium"),
     *                 @OA\Property(property="due_date", type="string", format="date-time", example="2025-05-02 13:12:11"),
     *                 @OA\Property(property="created_by", type="integer", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-25T13:12:11.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-25T13:12:11.000000Z"),
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
     *                 @OA\Property(property="title", type="array", @OA\Items(type="string", example="title is required")),
     *                 @OA\Property(property="assign_user_id", type="array", @OA\Items(type="string", example="assign_user_id is required")),
     *                 @OA\Property(property="status_id", type="array", @OA\Items(type="string", example="status_id is required")),
     *                 @OA\Property(property="epic_id", type="array", @OA\Items(type="string", example="epic_id is required"))
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

            $validated = TaskForm::add($data);
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
     *     path="/api/task/{id}",
     *     summary="Update an existing task",
     *     tags={"Task"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the task to update",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"title", "assign_user_id", "status_id", "epic_id"},
     *             @OA\Property(property="title", type="string", example="Updated Task"),
     *             @OA\Property(property="description", type="string", nullable=true, example="Updated task description."),
     *             @OA\Property(property="assign_user_id", type="integer", example=1),
     *             @OA\Property(property="priority", type="string", nullable=true, example="high"),
     *             @OA\Property(property="due_date", type="string", format="date-time", nullable=true, example="2025-05-02 13:12:11"),
     *             @OA\Property(property="status_id", type="integer", example=1),
     *             @OA\Property(property="epic_id", type="integer", example=1),
     *             @OA\Property(property="report_to_user_id", type="integer", nullable=true, example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful update of a task",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="Updated Task"),
     *                 @OA\Property(property="description", type="string", example="Updated task description."),
     *                 @OA\Property(property="status_id", type="integer", example=1),
     *                 @OA\Property(property="epic_id", type="integer", example=2),
     *                 @OA\Property(property="assign_user_id", type="integer", example=2),
     *                 @OA\Property(property="report_to_user_id", type="integer", example=2),
     *                 @OA\Property(property="priority", type="string", example="high"),
     *                 @OA\Property(property="due_date", type="string", format="date-time", example="2025-05-02 13:12:11"),
     *                 @OA\Property(property="created_by", type="integer", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-25T13:12:11.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-25T13:12:11.000000Z"),
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
     *                 @OA\Property(property="title", type="array", @OA\Items(type="string", example="title is required")),
     *                 @OA\Property(property="assign_user_id", type="array", @OA\Items(type="string", example="assign_user_id is required")),
     *                 @OA\Property(property="status_id", type="array", @OA\Items(type="string", example="status_id is required")),
     *                 @OA\Property(property="epic_id", type="array", @OA\Items(type="string", example="epic_id is required"))
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

            $validated = TaskForm::update($data);
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
     *     path="/api/task/{id}",
     *     summary="Get details of a specific task",
     *     tags={"Task"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the task to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of task details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="title", type="string", example="New Task"),
     *                 @OA\Property(property="description", type="string", example="This is a test task description."),
     *                 @OA\Property(property="status_id", type="integer", example=1),
     *                 @OA\Property(property="epic_id", type="integer", example=2),
     *                 @OA\Property(property="assign_user_id", type="integer", example=2),
     *                 @OA\Property(property="report_to_user_id", type="integer", example=2),
     *                 @OA\Property(property="priority", type="string", example="medium"),
     *                 @OA\Property(property="due_date", type="string", format="date-time", example="2025-05-02 13:12:11"),
     *                 @OA\Property(property="created_by", type="integer", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-25T13:12:11.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-25T13:12:11.000000Z"),
     *                 @OA\Property(property="id", type="integer", example=2)
     *             ),
     *             @OA\Property(property="message", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Task not found")
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
    public function destroy(string $id){
        $destroy = null;
        try{
           $destroy = $this->function->delete($id);

           $destroy = $this->mainHelper->buildResponse($destroy, 200);
        }catch(Exception $e){
            $destroy = $this->mainHelper->buildResponse(null, 500,  $e->getMessage());
        }

        return response()->json($destroy, $destroy['status']);
    }

     /**
     * @OA\Delete(
     *     path="/api/task/{id}",
     *     summary="Delete a specific task",
     *     tags={"Task"},
     *     operationId="deleteTaskById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the task to delete",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful deletion of the task",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Task not found")
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
}
