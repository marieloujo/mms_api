<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Resources\PortefeuillesResource;
use App\Services\Contract\ServiceInterface\PortefeuilleServiceInterface;
use App\Services\Contract\ServiceInterface\MarchandServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use App\Models\Contrat;
use App\Models\Direction;
use App\Models\Marchand;
use App\Models\SuperMarchand;
class PortefeuilleController extends Controller
{
    protected $portefeuilleService;
    protected $marchandService;


    public function __construct(PortefeuilleServiceInterface $portefeuilleService,
                    MarchandServiceInterface $marchandServiceInterface)
    {
        $this->portefeuilleService = $portefeuilleService;
        $this->marchandService = $marchandServiceInterface;
    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $portefeuilleData=$this->portefeuilleService->index();
        if(count($portefeuilleData)>0){
            return PortefeuillesResource::collection($portefeuilleData);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun portefeuille' ]], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($portefeuille)
    {
        $portefeuilleData=$this->portefeuilleService->read($portefeuille);
        if($portefeuilleData){
            return response()->json([ 'success' => ['data' => new PortefeuillesResource($portefeuilleData) ]], Response::HTTP_OK);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun portefeuille' ]], Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {

        return response()->json([ 'success' => ['message' => $this->save($request) ]], Response::HTTP_CREATED);
        try {

            if($this->save($request)){
                return response()->json([ 'success' => ['message' => 'Depôt effectué' ]], Response::HTTP_CREATED);
            }else{
                return response()->json([ 'errors' => ['message' => 'Echec' ]], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $portefeuille){
        $portefeuille=$this->portefeuilleService->update($request,$portefeuille);

        try {
            if($portefeuille){
                return response()->json([ 'success' => ['message' => 'Depôt effectué' ]], Response::HTTP_CREATED);
            }else{
                return response()->json([ 'errors' => ['message' =>  'Le portefeuille n\'a pas été mis à jour'  ]], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($portefeuille){
        try {
            if($this->portefeuilleService->delete($portefeuille)){
                return response()->json([ 'success' => ['message' => 'Le portefeuille à bien été supprimé' ]], Response::HTTP_OK);
            }else{
                return response()->json([ 'errors' => ['message' =>  'Le portefeuille n\'a pas été supprimé'  ]], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function save(Request $request){


        DB::beginTransaction();

		try {

            $portefeuille_data['montant']=$request['montant'];

            $portefeuille_data['contrat_id']=$request['contrat']['id'];

            $portefeuille_data['marchand_id']=$request['marchand']['id'];

            $portefeuille_data_request = new Request($portefeuille_data);

            $portefeuille=$this->portefeuilleService->create($portefeuille_data_request);

            $marchandD=Marchand::sharedLock()->findOrfail($portefeuille->marchand_id);
            $marchandD->credit_virtuel -=$portefeuille['montant'];
            $marchandD->update();

            //get nbre commission
            $commission=$portefeuille->montant/1000;

            //$marchand=$this->marchandService->read(Contrat::findOrfail($portefeuille->contrat_id)->marchand_id);
            
            $marchand=Marchand::sharedLock()->findOrfail(Contrat::findOrfail($portefeuille->contrat_id)->marchand_id);
           
            $marchand->commission +=$commission*185;
            $marchand->update();

            $supermarchand=SuperMarchand::sharedLock()->findOrfail($marchand->super_marchand_id);
            $supermarchand->commission +=$commission*65;
            $supermarchand->update();

            $direction=Direction::sharedLock()->findOrfail(1);
            $direction->commission +=$commission*750;
            $direction->update();

            //return response()->json([ 'success' => ['data' => new ContratsResource( $contrat) ]], Response::HTTP_OK);
            //return $contrat;

            DB::commit();
            return $portefeuille;;
		} catch (\Exception $e) {
		    DB::rollback();
		    // something went wrong
		    $message = $e->getMessage();
		    return response()->json(['error' => 1, 'message' => $message]);
		}

    }

}
