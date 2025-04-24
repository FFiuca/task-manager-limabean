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
    ){
        // $this->epicFunction = $epicFunction;
        // $this->mainHelper = $mainHelper;
    }

    /**
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
