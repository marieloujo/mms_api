<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\Resources\ContratsResource;
use App\Http\Resources\Resources\contrat\PortefeuillesResource;
use App\Services\Contract\ServiceInterface\AssurerServiceInterface;
use App\Services\Contract\ServiceInterface\BeneficeServiceInterface;
use App\Services\Contract\ServiceInterface\BeneficiaireServiceInterface;
use App\Services\Contract\ServiceInterface\ClientServiceInterface;
use App\Services\Contract\ServiceInterface\ContratServiceInterface;
use App\Services\Contract\ServiceInterface\DocumentServiceInterface;
use App\Services\Contract\ServiceInterface\PortefeuilleServiceInterface;
use App\Services\Contract\ServiceInterface\RegistrationServiceInterface;
use App\Models\Marchand;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ContratController extends Controller
{
    protected $contratService;
    protected $clientService;
    protected $beneficeService;
    protected $beneficiaireService;
    protected $portefeuilleService;
    protected $assurerService;
    protected $doumentService;
    protected $userService;

    private  $client;
    private  $assure;
    private $beneficiaire;

    public function __construct(ContratServiceInterface $contratService,ClientServiceInterface $clientServiceInterface,
        BeneficeServiceInterface $beneficeServiceInterface, BeneficiaireServiceInterface $beneficiaireServiceInterface,
        PortefeuilleServiceInterface $portefeuilleServiceInterface, AssurerServiceInterface $assurerServiceInterface,
        DocumentServiceInterface $documentServiceInterface, RegistrationServiceInterface $registrationServiceInterface
    )
    {
        $this->contratService = $contratService;
        $this->clientService = $clientServiceInterface;

        $this->beneficeService = $beneficeServiceInterface;
        $this->beneficiaireService = $beneficiaireServiceInterface;
        $this->assurerService = $assurerServiceInterface;
        $this->portefeuilleService = $portefeuilleServiceInterface;
        $this->documentService = $documentServiceInterface;
        $this->userService = $registrationServiceInterface;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contratData=$this->contratService->index();
        if(count($contratData)>0){
            return ContratsResource::collection($contratData);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun contrat' ]], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($contrat)
    {
        $contratData=$this->contratService->read($contrat);
        if($contratData){
            return response()->json([ 'success' => ['data' => new ContratsResource($contratData) ]], Response::HTTP_OK);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun contrat' ]], Response::HTTP_NOT_FOUND);
        }
    }
    public function showContrat($ref)
    {
        $contratData=$this->contratService->readContrat($ref);

        if($contratData){
            return response()->json([ 'contrat_id' => $contratData->id,'portefeuille'=> $contratData->portefeuilles->sum('montant')]);
            return response()->json([ 'success' => ['data' => new ContratsResource($contratData) ]], Response::HTTP_OK);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun contrat' ]], Response::HTTP_NOT_FOUND);
        }
    }
    public function store(Request $request)
    {

        try {
            

            return response()->json([ 'success' => ['message' => $this->save($request)]], Response::HTTP_CREATED);

            if($this->save($request)){
                return response()->json([ 'success' => ['message' => 'Contrat d\'assurance décès à bien été créer']], Response::HTTP_CREATED);
            }else{
                return response()->json([ 'success' => ['message' => 'Echec de création du contrat']], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(\Exception $e){
            DB::rollback();
            $message = $e->getMessage();
            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $contrat){

        try {
            if($this->contratService->update($request,$contrat)){
                return response()->json([ 'success' => ['message' => 'Depôt effectué']], Response::HTTP_CREATED);
            }else{
                return response()->json([ 'errors' => ['message' =>  'Le contrat n\'a pas été mis à jour'  ]], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($contrat){
        try {
            if($this->contratService->delete($contrat)){
                return response()->json([ 'success' => ['message' => 'Le contrat à bien été supprimé' ]], Response::HTTP_OK);
            }else{
                return response()->json([ 'errors' => ['message' =>  'Le contrat n\'a pas été supprimé'  ]], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function save(Request $request){


        DB::beginTransaction();

		try {

            try{
                if($request['client']['id'] == 0){
                        $client_data['profession']=$request['client']['profession'];
                        $client_data['employeur']=$request['client']['employeur'];
                        $client_request = new Request($client_data);

                        try{$this->client=$this->clientService->create($client_request);}
                        catch(\Exception $e){
                            DB::rollback();
                            $message = $e->getMessage();
                            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
                        }

                        $client_user_data=$request['client']['userable']['user'];

                        $client_user_data['userable_id']=$this->client->id;

                        $client_user_data['commune']=$client_user_data['commune']['id'];
                        $client_user_data['userable_type']='App\\Models\\Client';
                        $client_user_request = new Request($client_user_data);

                        if($request['client']['userable']['user']['id'] == 0){
                            
                            try{$user= $this->userService->register($client_user_request);}
                            catch(\Exception $e){
                                DB::rollback();
                                $message = $e->getMessage();
                                return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
                            }
                        }else{
                            try{$user= $this->userService->register($client_user_request,$request['client']['userable']['user']['id']);}
                            catch(\Exception $e){
                                DB::rollback();
                                $message = $e->getMessage();
                                return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
                            }
                        }
                }else{
                    $this->client=$request['client'];
                    
                }
            }catch(\Exception $e){
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
                        
            try{
                if($request['assure']['id'] == 0){

                    $assure_data['profession']=$request['assure']['profession'];
                    $assure_data['employeur']=$request['assure']['employeur'];
                    $assure_data['etat']=false;
                    $assure_request = new Request($assure_data);
                    try{$this->assure=$this->assurerService->create($assure_request);}
                    catch(\Exception $e){
                        DB::rollback();
                        $message = $e->getMessage();
                        return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
                    }

                    $assure_user_data=$request['assure']['userable']['user'];
                    $assure_user_data['userable_id']=$this->assure->id;
                    $assure_user_data['commune']=$assure_user_data['commune']['id'];
                    $assure_user_data['userable_type']='App\\Models\\Assurer';
                    $assure_user_request = new Request($assure_user_data);

                    if($request['assure']['userable']['user']['id'] == 0){
                        try{

                            $user= $this->userService->register($assure_user_request);
                        }catch(\Exception $e){
                            DB::rollback();
                            $message = $e->getMessage();
                            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
                        }
                    }else{
                        try{$user= $this->userService->register($assure_user_request,$request['assure']['userable']['user']['id']);}
                        catch(\Exception $e){
                            DB::rollback();
                            $message = $e->getMessage();
                            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
                        }
                    }

                }else{
                    $this->assure=$request['assure'];
                }
            }catch(\Exception $e){
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            

            $contrat_data=$this->getContratData($request->all(),$this->client['id'],$this->assure['id']);
            
            $contrat_request = new Request($contrat_data);
            try{$contrat=$this->contratService->create($contrat_request);}
            catch(\Exception $e){
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $documents_data=$request['documents'];

            $i=0;
            foreach ($documents_data as $document){
                $i++;
               
                $document_data['url']                  =$this->createImageFromBase64($document,$contrat->numero_contrat,$i);
                $document_data['documentable_type']     ='App\\Models\\Contrat';
                $document_data['documentable_id']       =$contrat->id;

                $document_data_request = new Request($document_data);
                
                try{$document=$this->documentService->create($document_data_request);}
                catch(\Exception $e){
                    DB::rollback();
                    $message = $e->getMessage();
                    return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
                }


            }
            
            $benefices_data=$request['beneficiaire'];
            
            $i=0;

            try{
                
                foreach ($benefices_data as $benefice){
 
                    $benefice_data['statut']=$benefice['statut'];
                    $benefice_data['taux']=$benefice['taux'];
                    
                    try{    
                        if($benefice['beneficiaire']['id'] == 0){

                            $benefice_beneficiaire_data=$benefice['beneficiaire']['userable']['user'];

                            $benefice_beneficiaire_request = new Request($benefice_beneficiaire_data);

                            try{$this->beneficiaire=$this->beneficiaireService->create($benefice_beneficiaire_request);}
                            catch(\Exception $e){
                                DB::rollback();
                                $message = $e->getMessage();
                                return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
                            }

                            $beneficiaire_user_data=$benefice_beneficiaire_data;

                            $beneficiaire_user_data['userable_id']=$this->beneficiaire->id;
                            $beneficiaire_user_data['commune']=$beneficiaire_user_data['commune']['id'];
                            $beneficiaire_user_data['userable_type']='App\\Models\\Beneficiaire';
                            $beneficiaire_user_request = new Request($beneficiaire_user_data);
                            if($benefice['beneficiaire']['userable']['user']['id'] == 0){
                                    try{$user= $this->userService->register($beneficiaire_user_request);}
                                    catch(\Exception $e){
                                        DB::rollback();
                                        $message = $e->getMessage();
                                        return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
                                    }
                            }else{
                                    try{$user= $this->userService->register($beneficiaire_user_request,$request['beneficiaire']['userable']['user']['id']);}
                                    catch(\Exception $e){
                                        DB::rollback();
                                        $message = $e->getMessage();
                                        return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
                                    }
                            } 
            

                        }else{
                            $this->beneficiaire=$request['beneficiaire'];
                        }
                    }catch(\Exception $e){
                        DB::rollback();
                        $message = $e->getMessage();
                        return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
                    }


                    $benefice_data['beneficiaire_id']=$this->beneficiaire['id'];
                    
                    $benefice_data['contrat_id']=$contrat->id;

                    $benefice_request = new Request($benefice_data);
                    try{$benefice[$i]=$this->beneficeService->create($benefice_request);}
                    catch(\Exception $e){
                        DB::rollback();
                        $message = $e->getMessage();
                        return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                    $i++;

                }
            }
            catch(\Exception $e){
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            try{    
                $marchandC=Marchand::sharedLock()->findOrfail($request['marchand']['id']);
                $marchandC->credit_virtuel -=1000;
                $marchandC->update();
            
            }catch(\Exception $e){
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            DB::commit();
            return $contrat;
		} catch (\Exception $e) {
		    DB::rollback();
		    // something went wrong
		    $message = $e->getMessage();
		    return response()->json(['error' => 1, 'message' => $message]);
		}

    }

    public function getContratData(array $request,$client_id,$assure_id){


        $contrat_code = $request['marchand']['userable']['user']['commune']['departement']['code'].
        $request['marchand']['userable']['userable']['super_marchand_id'].
        $request['marchand']['matricule'].
        $client_id;

            $contrat['numero_contrat']              = $contrat_code.Str::random(3);
            $contrat['duree']                       = 1 ;
            $contrat['frais_dossier']               = 1000;
            $contrat['garantie']                    = 1000000 ;
            $contrat['prime']                       = 1000 ;
            $contrat['marchand_id']                 = $request['marchand']['id'];
            $contrat['date_debut']                  = Carbon::now();
            $contrat['date_echeance']               = Carbon::now()->addWeek()->addYear();
            $contrat['date_effet']                  = Carbon::now()->addWeek();
            $contrat['date_fin']                    = Carbon::now()->addWeek()->addYear();
            $contrat['fin']                         = false;
            $contrat['valider']                     = false;
            $contrat['client_id']                   = $client_id;
            $contrat['assure_id']                   = $assure_id;
            $contrat['numero_police_assurance']     = $contrat_code ;

        return $contrat;
    }

    public function validations(RegisterRequest $request){
        $validated = $request->validated();
        if($validated){
            return Response()->json([ 'success' => ['message' => 'Ok']], Response::HTTP_OK);
        }
    }

    public function assurerValidation($assureId){
        $contrats=$this->contratService->index()->where('assure_id',$assureId);
        if(count($contrats)>=3){
            return response()->json([ 'success' => ['message' => true]], Response::HTTP_OK);
        }else{return response()->json([ 'success' => ['message' => false]], Response::HTTP_OK);}

    }

    public function createImageFromBase64(array $request,$name,$number){ 
        $file_data = $request['url']; 
        $file_name = 'documents/'.$name.'/'.$name.'_num'.$number.'_'.Carbon::now()->format('Y_m_d').'.png'; //generating unique file name; 
        @list($type, $file_data) = explode(';', $file_data);
        @list(, $file_data) = explode(',', $file_data); 
        if($file_data!=""){ // storing image in storage/app/public Folder 
            \Storage::disk('public')->put($file_name,base64_decode($file_data)); 
        } 
        return 'storage/'.$file_name;
    }

    public function createClient(array $request,$id){

        return $client;
    }

    public function createAssurer(array $request,$id){

        return $assurer;
    }

    public function createBeneficiaire(array $request,$id){

        return $beneficiaire;
    }


}
