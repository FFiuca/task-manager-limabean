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
        // $this->epicFunction = $epicFunction;
        // $this->mainHelper = $mainHelper;
    }


    /**
     * Store a newly created resource in storage.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
