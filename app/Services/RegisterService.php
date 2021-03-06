<?php

namespace App\Services;

use App\Http\Requests\User\RegisterRequest;
use App\Models\Userable;
use App\Models\OAuthClient;
use App\Repositories\Contracts\RepositoryInterface;
use App\Services\Contract\ServiceInterface\RegistrationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Symfony\Component\HttpFoundation\Response;
class RegisterService implements RegistrationServiceInterface
{
    protected $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function register(Request $request,$user_id=null)
    {   
        $user=null;
        DB::beginTransaction();
        try {
            if($user_id==null){
                try {     
                
                $request['commune_id']=$request['commune'];
                $user = $this->repository->create($request->all());
                
                }
                catch(\Exception $e){
                    DB::rollback();
                    $message = $e->getMessage();
                    return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }else{
                $user['id']=$user_id;
            }

	        try {
                $request['password'] =Hash::make($request['telephone'].'password');
                $request['login']=$request['telephone'];
                $request['user_id']=$user['id'];
		        $userable = Userable::create($request->all());
            }
            catch(\Exception $e){
				DB::rollback();
				$message = $e->getMessage();
		    	return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
	        try {
		        $oauth_client = OAuthClient::create([
		       		"user_id" => $userable->id,
		       		"secret" => Str::random(40),
		       		"name" => "Password Grant",
		       		"revoked" => 0,
		       		"password_client" => 1,
		       		"personal_access_client" => 0,
					"redirect" => "http://localhost",
                ]);
            }
            catch(\Exception $e){
				DB::rollback();
				$message = $e->getMessage();
		    	return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            DB::commit(); 
            return $user;
        }
        catch(\Exception $e){
            DB::rollback();
            $message = $e->getMessage();
            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_NOT_FOUND);
        }
    }
}