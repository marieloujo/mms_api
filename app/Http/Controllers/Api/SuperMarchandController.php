<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Resources\SuperMarchand\MarchandResources;
use App\Http\Resources\Resources\SuperMarchandsResource;

use App\Http\Resources\Resources\ComptesResource;
use App\Services\Contract\ServiceInterface\SuperMarchandServiceInterface;
use App\Http\Controllers\Controller;
use App\Services\Contract\ServiceInterface\MarchandServiceInterface;
use App\Services\Contract\ServiceInterface\RegistrationServiceInterface;
use App\Services\Contract\ServiceInterface\UserableServiceInterface;
use App\Services\Contract\ServiceInterface\UserServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\New_;
use Symfony\Component\HttpFoundation\Response;

class SuperMarchandController extends Controller
{

    protected $supermarchandService;

    protected $marchandService;
    protected $registerService;
    protected $userService;
    protected $userableService;

    public function __construct(SuperMarchandServiceInterface $supermarchandServiceInterface, MarchandServiceInterface $marchandServiceInterface, UserableServiceInterface $userableServiceInterface, UserServiceInterface $userServiceInterface, RegistrationServiceInterface $registrationServiceInterface)
    {
        $this->marchandService  = $marchandServiceInterface;
        $this->registerService  = $registrationServiceInterface;
        $this->userService      = $userServiceInterface;
        $this->userableService      = $userableServiceInterface;
        $this->supermarchandService = $supermarchandServiceInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supermarchandData = $this->supermarchandService->index();
        if (count($supermarchandData) > 0) {
            return SuperMarchandsResource::collection($supermarchandData);
        } else {
            return response()->json(['success' => ['message' => 'Aucun supermarchand']], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($supermarchand)
    {
        $supermarchandsData = $this->supermarchandService->read($supermarchand);
        if ($supermarchandsData) {
            return response()->json(['success' => ['data' => new SuperMarchandsResource($supermarchandsData)]], Response::HTTP_OK);
        } else {
            return response()->json(['success' => ['message' => 'Aucun supermarchand']], Response::HTTP_NOT_FOUND);
        }
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            try {
                $supermarchand = $this->supermarchandService->create($request);
            } catch (\Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => ['message' => $message]], Response::HTTP_NOT_FOUND);
            }
            try {
                $supermarchand_user_data = $request['userable']['user'];
                $supermarchand_user_data['actif'] = 1;
                $supermarchand_user_data['userable_id'] = $supermarchand->id;
                $supermarchand_user_data['commune'] = $supermarchand_user_data['commune']['id'];
                $supermarchand_user_data['userable_type'] = 'App\\Models\\SuperMarchand';
                $supermarchand_user_request = new Request($supermarchand_user_data);
                $user = $this->registerService->register($supermarchand_user_request);
                
            } catch (\Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => ['message' => $message]], Response::HTTP_NOT_FOUND);
            }
            DB::commit();
            //return response()->json(['success' => ['data' => $user]], Response::HTTP_CREATED);
            return response()->json(['success' => ['data' => new SuperMarchandsResource($supermarchand)]], Response::HTTP_OK);
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

    public function update(Request $request, $supermarchand)
    {

        DB::beginTransaction();
        try {

            try {
	    
	    $this->supermarchandService->update($request, $supermarchand);
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
            return response()->json(['errors' => ['message' =>  'Le super marchand a bien été mis à jour']], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['errors' => ['message' => $message]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($supermarchand)
    {
        DB::beginTransaction();
        try {

            try {
                foreach ($this->supermarchandService->getMarchands($supermarchand) as $key => $marchand) {
                    # code...

                    $marchand_controller = new MarchandController($this->marchandService, $this->userableService, $this->userService, $this->registerService);
                    $marchand_controller->destroy($marchand->id);
                }
            } catch (\Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => ['message' => $message]], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            try {
                $this->userService->delete($this->supermarchandService->read($supermarchand)->userable()->user_id);
            } catch (\Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => ['message' => $message]], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            try {
                $this->userableService->delete($this->supermarchandService->read($supermarchand)->userable()->id);
            } catch (\Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => ['message' => $message]], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            try {
                $this->supermarchandService->delete($supermarchand);
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


    public function getCompte($supermarchand)
    {
        $compteData = $this->supermarchandService->getCompte($supermarchand);

        if ($compteData) {
            return response()->json(['success' => ['commission' => $compteData]], Response::HTTP_OK);
        } else {
            return response()->json(['success' => ['message' => 'Aucune commission']], Response::HTTP_NOT_FOUND);
        }
    }


    public function getComptes($supermarchand, $date = null)
    {

        if ($date) {
            $end = Carbon::parse((Carbon::parse($date))->addDay())->format('Y-m-d');
            $start = Carbon::parse((Carbon::parse($date)->subMonths(3))->addDay())->format('Y-m-d');
            $comptesData = $this->supermarchandService->getComptes($supermarchand, $end, $start);
        } else {
            $end = Carbon::parse((Carbon::now())->addDay())->format('Y-m-d');
            $start = Carbon::parse(((Carbon::now())->subMonths(3))->addDay())->format('Y-m-d');
            $comptesData = $this->supermarchandService->getComptes($supermarchand, $end, $start);
        }

        if (count($comptesData) > 0) {
            return ComptesResource::collection($comptesData);
        } else {
            return response()->json(['success' => ['message' => 'Aucun comptes']], Response::HTTP_NOT_FOUND);
        }
    }

    public function getMarchands($supermarchand)
    {

        $marchandsData = $this->supermarchandService->getMarchands($supermarchand);
        if (count($marchandsData) > 0) {
            return MarchandResources::collection($marchandsData);
        } else {
            return response()->json(['success' => ['message' => 'Aucun marchand']], Response::HTTP_NOT_FOUND);
        }
    }
}
