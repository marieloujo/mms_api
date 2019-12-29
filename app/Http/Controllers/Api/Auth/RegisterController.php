<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterRequest;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use App\Services\Contract\ServiceInterface\RegistrationServiceInterface;
class RegisterController extends Controller
{

    protected $registerService;

    public function __construct(RegistrationServiceInterface $registerService)
    {
        $this->registerService = $registerService;
    }

    public function register(Request $request)
    {/* 

        $request['prospect']=1;
        $request['actif']=0;
        $request['commune']=$request['commune']['id'];
        $request['marchand_id']=$request['marchand']['id'];
        $request['userable_id']=null;
        $request['userable_type']=null;

        if($this->registerService->register($request)){
            return response()->json([ 'success' => ['message' => 'Prospect enregistré' ]], Response::HTTP_CREATED);
        }else{
            return response()->json([ 'success' => ['message' => 'Échec d\'enregistrement' ]], Response::HTTP_INTERNAL_SERVER_ERROR);
        } */

    }

    public  function  regis(){
        return 'ok';
    }
}
