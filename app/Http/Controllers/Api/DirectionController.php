<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Resources\DirectionsResource;
use App\Services\Contract\ServiceInterface\DirectionServiceInterface;
use App\Services\Contract\ServiceInterface\RegistrationServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class DirectionController extends Controller
{
    protected $directionService;
    protected $registerService;

    public function __construct(DirectionServiceInterface $directionService,RegistrationServiceInterface $registrationServiceInterface)
    {
        $this->directionService = $directionService;
        $this->registerService  = $registrationServiceInterface;
    } 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $directionData=$this->directionService->index();
        if(count($directionData)>0){
            return DirectionsResource::collection($directionData);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun direction' ]], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($direction)
    {
        $directionData=$this->directionService->read($direction);
        if($directionData){
            return response()->json([ 'success' => ['data' => new DirectionsResource($directionData) ]], Response::HTTP_OK);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun direction' ]], Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {

        DB::beginTransaction();
        try {
            try {
                $direction = $this->directionService->create($request);
            } catch (\Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => ['message' => $message]], Response::HTTP_NOT_FOUND);
            }
            try {
                $direction_user_data = $request['userable']['user'];
                $direction_user_data['actif'] = 1;
                $direction_user_data['userable_id'] = $direction->id;
                $direction_user_data['commune'] = $direction_user_data['commune']['id'];
                $direction_user_data['userable_type'] = 'App\\Models\\Direction';
                $direction_user_request = new Request($direction_user_data);
                $user = $this->registerService->register($direction_user_request);
            } catch (\Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => ['message' => $message]], Response::HTTP_NOT_FOUND);
            }
            DB::commit();
            //return response()->json(['success' => ['data' => $user]], Response::HTTP_CREATED);
            return response()->json([ 'success' => ['data' => $direction /* new DirectionsResource( $direction) */ ]], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            $message = $e->getMessage();
            return response()->json(['errors' => ['message' => $message]], Response::HTTP_NOT_FOUND);
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $direction){
        
        try {         
            if($this->directionService->update($request,$direction)){
                return $this->show($direction);
            }else{
                return response()->json([ 'errors' => ['message' =>  'Le direction n\'a pas été mis à jour'  ]], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($direction){
        try {       
            if($direction==1){
                return response()->json([ 'success' => ['message' => 'Impossible de supprimé ce compte' ]], Response::HTTP_OK);
            }else{
                if($this->directionService->delete($direction)){
                    return response()->json([ 'success' => ['message' => 'Le direction à bien été supprimé' ]], Response::HTTP_OK);
                }else{
                    return response()->json([ 'errors' => ['message' =>  'Le direction n\'a pas été supprimé'  ]], Response::HTTP_INTERNAL_SERVER_ERROR);
                } 
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
