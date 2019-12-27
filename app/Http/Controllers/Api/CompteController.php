<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComptesResource;
use App\Models\Direction;
use App\Models\Marchand;
use App\Services\Contract\ServiceInterface\CompteServiceInterface;
use App\Services\Contract\ServiceInterface\DirectionServiceInterface;
use App\Services\Contract\ServiceInterface\MarchandServiceInterface;
use App\Services\Contract\ServiceInterface\SuperMarchandServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CompteController extends Controller
{
    protected $compteService;
    protected $marchandService;
    protected $supermarchandService;
    protected $directionService;

    public function __construct(CompteServiceInterface $compteServiceInterface,MarchandServiceInterface $marchandServiceInterface,SuperMarchandServiceInterface $supermarchandServiceInterface,DirectionServiceInterface $directionServiceInterface)
    {
        $this->compteService = $compteServiceInterface;
        $this->marchandService = $marchandServiceInterface;
        $this->supermarchandService = $supermarchandServiceInterface;
        $this->directionService = $directionServiceInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compteData=$this->compteService->index();
        if(count($compteData)>0){
            return ComptesResource::collection($compteData);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun compte' ]], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($compte)
    {
        $comptesData=$this->compteService->read($compte);
        if($comptesData){
            return response()->json([ 'success' => ['data' => new ComptesResource($comptesData) ]], Response::HTTP_OK);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun compte' ]], Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {
        return response()->json([ 'success' => ['message' => $this->transfert($request) ]]);
        try {
            if($this->transfert($request)){
                return response()->json([ 'success' => ['message' => 'Transfert effectué' ]], Response::HTTP_CREATED);
            }else{
                return response()->json([ 'errors' => ['message' => 'Echec de transfert' ]], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $compte){

        try {
            if($this->compteService->update($request,$compte)){
                return $this->show($compte);
            }else{
                return response()->json([ 'errors' => ['message' =>  'Le compte n\'a pas été mis à jour'  ]], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($compte){
        try {
            if($this->compteService->delete($compte)){
                return response()->json([ 'success' => ['message' => 'Le compte à bien été supprimé' ]], Response::HTTP_OK);
            }else{
                return response()->json([ 'errors' => ['message' =>  'Le compte n\'a pas pu être supprimé'  ]], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function transfert(Request $request,$user){

        DB::beginTransaction();

        try {
            //$marchand=$this.$this->marchandService->read($marchand);
            //$id=Auth::user()->userable->id;

            $marchandT=Marchand::sharedLock()->findOrfail($user);
            $marchandT->commission -=$request['montant'];
            $marchandT->update();

            $compteT_data['montant']=$request['montant'];
            $compteT_data['compte']='commission';
            $compteT_data['compteable_id']=$marchandT->id;
            $compteT_data['compteable_type']='App\\Models\\Marchand';

            $compteT_data_request = new Request($compteT_data);
            $compteT=$this->compteService->create($compteT_data_request);

//                          //                      //                  //
            $id=$request['compteable']['id'];
                        
            $marchand=Marchand::sharedLock()->findOrfail($id);
            $marchand->credit_virtuel +=$request['montant'];
            $marchand->update();

            $compte_data['montant']         =$request['montant'];
            $compte_data['compte']          ='credit_virtuel';
            $compte_data['compteable_id']   =$marchand->id;
            $compte_data['compteable_type'] ='App\\Models\\Marchand';

            $compte_data_request = new Request($compte_data);
            $compte=$this->compteService->create($compte_data_request);

            DB::commit();
            return $compte;
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            $message = $e->getMessage();
            return response()->json(['error' => 1, 'message' => $message]);
        }

    }
}
