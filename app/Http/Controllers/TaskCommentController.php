<?php

namespace App\Http\Controllers;

use App\Forms\TaskCommentForm;
use App\Functions\Task\TaskCommentFunction;
use App\Helper\MainHelper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskCommentController extends Controller
{

    function __construct(
        private TaskCommentFunction $function,
        private MainHelper $mainHelper
    ){
    }


    /**
     * @OA\Post(
     *     path="/api/task/comment",
     *     summary="Create a new task comment",
     *     tags={"Task Comment"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"task_id", "comment"},
     *             @OA\Property(property="task_id", type="integer", example=1, description="The ID of the task"),
     *             @OA\Property(property="comment", type="string", example="This is a test comment.", description="The comment text")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful creation of a task comment",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="task_id", type="integer", example=1),
     *                 @OA\Property(property="comment", type="string", example="This is a test comment."),
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
     *                 @OA\Property(property="task_id", type="array", @OA\Items(type="string", example="task_id is required")),
     *                 @OA\Property(property="comment", type="array", @OA\Items(type="string", example="comment is required"))
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

            $validated = TaskCommentForm::add($data);
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
     *     path="/api/task/comment/{id}",
     *     summary="Update an existing task comment",
     *     tags={"Task Comment"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the task comment to update",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"comment"},
     *             @OA\Property(property="comment", type="string", example="Updated comment.", description="The updated comment text")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful update of a task comment",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="comment", type="string", example="Updated comment."),
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
     *                 @OA\Property(property="comment", type="array", @OA\Items(type="string", example="comment is required"))
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
    public function update(Request $request, string $id)
    {
        $update = null;
        try{
            $data = $request->post();

            $validated = TaskCommentForm::update($data);
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
     * @OA\Delete(
     *     path="/api/task/{id}",
     *     summary="Delete a specific task",
     *     tags={"Task"},
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
