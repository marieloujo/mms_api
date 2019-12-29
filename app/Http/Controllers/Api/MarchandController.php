<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Resources\ComptesResource;
use App\Http\Resources\Resources\Marchand\ClientResources;
use App\Http\Resources\Resources\Marchand\ContratResources;
use App\Http\Resources\Resources\MarchandsResource;
use App\Http\Resources\Resources\TransactionResources;
use App\Http\Resources\Resources\UserResources;
use App\Services\Contract\ServiceInterface\MarchandServiceInterface;
use App\Services\Contract\ServiceInterface\RegistrationServiceInterface;
use App\Services\Contract\ServiceInterface\UserableServiceInterface;
use App\Services\Contract\ServiceInterface\UserServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class MarchandController extends Controller
{

    protected $marchandService;
    protected $registerService;
    protected $userService;
    protected $userableService;


    public function __construct(MarchandServiceInterface $marchandServiceInterface, UserableServiceInterface $userableServiceInterface, UserServiceInterface $userServiceInterface, RegistrationServiceInterface $registrationServiceInterface)
    {
        $this->marchandService  = $marchandServiceInterface;
        $this->registerService  = $registrationServiceInterface;
        $this->userService      = $userServiceInterface;
        $this->userableService      = $userableServiceInterface;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marchandData=$this->marchandService->index();
        if(count($marchandData)>0){
            return MarchandsResource::collection($marchandData);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun marchand' ]], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($marchand)
    {
        $marchandsData=$this->marchandService->read($marchand);
        if($marchandsData){
            return response()->json([ 'success' => ['data' => new MarchandsResource($marchandsData) ]], Response::HTTP_OK);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun marchand' ]], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getMarchand($marchand)
    {
        $marchandsData=$this->marchandService->read($marchand);
        if($marchandsData){
            return response()->json([ 'success' => ['data' => $marchandsData ]], Response::HTTP_OK);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun marchand' ]], Response::HTTP_NOT_FOUND);
        }
    }

    public function getByMatricule($matricule)
    {
        $marchandsData=$this->marchandService->getMarchand($matricule);
        if($marchandsData){
            return response()->json([ 'success' => ['data' => new MarchandsResource($marchandsData) ]], Response::HTTP_OK);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun marchand' ]], Response::HTTP_NOT_FOUND);
        }
    }
    
    public function store(Request $request){
        DB::beginTransaction();
        try {
            try {
                $marchand = $this->marchandService->create($request);
            } catch (\Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => ['message' => $message]], Response::HTTP_NOT_FOUND);
            }
            try {
                $marchand_user_data = $request['userable']['user'];
                $marchand_user_data['actif'] = 1;
                $marchand_user_data['userable_id'] = $marchand->id;
                $marchand_user_data['commune'] = $marchand_user_data['commune']['id'];
                $marchand_user_data['userable_type'] = 'App\\Models\\Marchand';
                $marchand_user_request = new Request($marchand_user_data);
                $user = $this->registerService->register($marchand_user_request);
            } catch (\Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => ['message' => $message]], Response::HTTP_NOT_FOUND);
            }
            DB::commit();
            //return response()->json(['success' => ['data' => $user]], Response::HTTP_CREATED);
            return response()->json([ 'success' => ['data' => new MarchandsResource( $marchand) ]], Response::HTTP_OK);
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

    public function update(Request $request, $marchand){

        DB::beginTransaction();
        try {

            try {
                $this->marchandService->update($request, $marchand);
            } catch (\Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => ['message' => $message]], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            try {

                $requestA = $request['userable']['user'];
                $requestA['commune_id'] = $requestA['commune']['id'];
                $requestA = new Request($requestA);

                $this->userService->update($requestA, $requestA['id']);
            } catch (\Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => ['message' => $message]], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            DB::commit();
            return response()->json(['errors' => ['message' =>  'Le marchand a bien été mis à jour']], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['errors' => ['message' => $message]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($marchand){
        DB::beginTransaction();
        try {/* 
            if($this->marchandService->delete($marchand)){
                return response()->json([ 'success' => ['message' => 'Le marchand à bien été supprimé' ]], Response::HTTP_OK);
            }else{
                return response()->json([ 'errors' => ['message' =>  'Le marchand n\'a pas pu être supprimé'  ]], Response::HTTP_INTERNAL_SERVER_ERROR);
            } */

            try {
                $this->userService->delete($this->marchandService->read($marchand)->userable()->user_id);
            } catch (\Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => ['message' => $message]], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            try {
                $this->userableService->delete($this->marchandService->read($marchand)->userable()->id);
            } catch (\Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => ['message' => $message]], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            try {
                $this->marchandService->delete($marchand);
            } catch (\Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => ['message' => $message]], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            DB::commit();
            return response()->json(['errors' => ['message' =>  'Le marchand a bien été supprimé ']], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['errors' => ['message' => $message]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCompte($marchand){
        $compteData=$this->marchandService->getCompte($marchand);

        if($compteData){
            return response()->json([ 'success' => ['data' => new ComptesResource($compteData) ]], Response::HTTP_OK);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun credit virtuel' ]], Response::HTTP_NOT_FOUND);
        }
    }


    public function getComptes($marchand,$date=null){

        if($date){
            $end=Carbon::parse((Carbon::parse($date))->addDay())->format('Y-m-d');
            $start=Carbon::parse((Carbon::parse($date)->subMonths(3))->addDay())->format('Y-m-d');
            $comptesData=$this->marchandService->getComptes($marchand,$end,$start);
        }else{
            $end=Carbon::parse((Carbon::now())->addDay())->format('Y-m-d');
            $start=Carbon::parse(((Carbon::now())->subMonths(3))->addDay())->format('Y-m-d');
            $comptesData=$this->marchandService->getComptes($marchand,$end,$start);
        }

        if(count($comptesData)>0){
            return ComptesResource::collection($comptesData);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun comptes' ]], Response::HTTP_NOT_FOUND);
        }
    }

    public function getTransactions($marchand){

        $marchandsData=$this->marchandService->getTransactions($marchand);
        if(count($marchandsData)>0){
            return TransactionResources::collection($marchandsData);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucune transaction' ]], Response::HTTP_NOT_FOUND);
        }
    }

    public function getTransaction($marchand,$date=null){
        $transactionsData=null;
        if($date){
            $end=Carbon::parse((Carbon::parse($date))->addDay())->format('Y-m-d');
            $start=Carbon::parse((Carbon::parse($date)->subMonths(3))->addDay())->format('Y-m-d');
            $transactionsData=$this->marchandService->getLastTransactions($marchand,$end,$start);
        }else{
            $start=Carbon::parse((Carbon::now()->subWeek())->addDay())->format('Y-m-d');
            $end=Carbon::parse((Carbon::now())->addDay())->format('Y-m-d');
            $transactionsData=$this->marchandService->getLastTransactions($marchand,$end,$start);
        }

        if(count($transactionsData)>0){
            return TransactionResources::collection($transactionsData);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucune transaction' ]], Response::HTTP_NOT_FOUND);
        }
    }

    public function getClients($marchand){
        $clientsData=$this->marchandService->getClients($marchand);
        if(count($clientsData)>0){
            return ClientResources::collection($clientsData);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun client' ]], Response::HTTP_NOT_FOUND);
        }
    }

    public function getProspects($marchand){
        $marchandsData=$this->marchandService->getProspects($marchand);
        if(count($marchandsData)>0){
            return UserResources::collection($marchandsData);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun prospect' ]], Response::HTTP_NOT_FOUND);
        }
    }

    public function getContrats($marchand,$client){
        $marchandsData=$this->marchandService->getContrats($marchand,$client);
        if(count($marchandsData)>0){
            return ContratResources::collection($marchandsData);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun contrat' ]], Response::HTTP_NOT_FOUND);
        }
    }

    public function getWaitingContrats($marchand){
        $marchandsData=$this->marchandService->getWaitingContrats($marchand);
        if(count($marchandsData)>0){
            return ContratResources::collection($marchandsData);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun contrat en attente' ]], Response::HTTP_NOT_FOUND);
        }
    }
}
