<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use App\Notifications\TaskCompleted;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\Resources\Collection\ConversationsUserResource;
use App\Http\Resources\Resources\Collection\NotificationsUserResource;
use App\Http\Resources\Resources\UserResources;
use App\Http\Resources\Resources\UsersResource;
use App\Models\Conversation;
use App\Services\Contract\ServiceInterface\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }
    public function getAuthenticatedUserable()
    {
        return $this->userService->getAuthenticatedUserable();
    }

    public function getDiscussions($user)
    {
        $conversationsUser = $this->userService->getDiscussions($user);

        if(count($conversationsUser)>0){
            return ConversationsUserResource::collection($conversationsUser);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucune discussion' ]], Response::HTTP_NOT_FOUND);
        }

    }

    public function notifications($user)
    {
        $result = $this->userService->getNotifications($user);
        if(count($result)>0){
            return NotificationsUserResource::collection($result);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucune notification' ]], Response::HTTP_NOT_FOUND);
        }
        
    }

    public function markReadNotifications($auth){
        $user=$this->userService->read($auth);
        $user->notifications;
        $unReadNotifications=$user->unReadNotifications;
        if(count($unReadNotifications)>0){
            $unReadNotifications->markAsRead();
        }
    }  

    public function sendNotifications(Request $request,$user_id){
        $user=$this->userService->read($user_id);
        if($user){
            $message = new Message;
            $message->body = $request['body'];
            $newNotifications = $user->notify(new TaskCompleted($message));
            if (!$newNotifications) {
                return response()->json(['success' => ['message' => 'La notification a bien été envoyée']], Response::HTTP_CREATED);
            } else {
                return response()->json(['success' => ['message' => 'Échec d\'envoi de la notification']], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }else{
            return response()->json(['success' => ['message' => 'Aucun utilisateur']], Response::HTTP_NOT_FOUND);
        }
    }

    public function unReadNotifications($auth){
        
        $user=$this->userService->read($auth);
         $unReadNotifications=$user->unReadNotifications;
        if(count($unReadNotifications)>0){
            return NotificationsUserResource::collection($unReadNotifications);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucune nouvelle notification' ]], Response::HTTP_NOT_FOUND);
        }
    }
    
    public function getNotifications($auth){
        $user=$this->userService->read($auth);
        $notifications=$user->notifications;
        if(count($notifications)>0){
            return NotificationsUserResource::collection($notifications);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucune notification' ]], Response::HTTP_NOT_FOUND);
        }
    }

    public function index(){
        $userData=$this->userService->index();
        if(count($userData)>0){
            return UserResources::collection($userData);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun utilisateur' ]], Response::HTTP_NOT_FOUND);
        }
    }

    public function show($user){
        $userData=$this->userService->read($user);
        if($userData){
            return response()->json([ 'success' => ['data' => new UserResources($userData) ]], Response::HTTP_OK);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun utilisateur trouvé' ]], Response::HTTP_NOT_FOUND);
        }
    }

    public function readByNom($nom){
        $userData=$this->userService->getUserByNom($nom);
        if(count($userData)>0){
            return UsersResource::collection($userData);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun utilisateur de ce nom' ]], Response::HTTP_NOT_FOUND);
        }
    }

    public function readByPhone($phone){
        $userData=$this->userService->getUserByPhone($phone);
        if($userData){
            return response()->json([ 'success' => ['data' => new UsersResource($userData) ]], Response::HTTP_OK);
        }else{
            return response()->json([ 'success' => ['message' => 'Aucun utilisateur trouvé' ]], Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $registerRequest, $user){
        try {
            return response()->json([ 'success' => ['data' => new UserResources( $this->userService->update($registerRequest,$user)) ]], Response::HTTP_OK);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_NOT_FOUND);
        }
    }

    public function delete($user){
        try {
            return response()->json([ 'success' => ['message' => $this->userService->delete($user) ]], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['errors' => [ 'message' => $message]],Response::HTTP_NOT_FOUND);
        }
    }



    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            try {
                $user_data = $request->all();
                $user_data['prospect'] = 1;
                $user_data['commune_id'] = $user_data['commune']['id'];

                $user_data['marchand_id'] = $request['marchand']['id'];
                $user_request = new Request($user_data);
                $user = $this->userService->save($user_request);
            } catch (\Exception $e) {
                DB::rollback();
                $message = $e->getMessage();
                return response()->json(['errors' => ['message' => $message]], Response::HTTP_NOT_FOUND);
            }
            DB::commit();
            return response()->json(['success' => ['message' => 'Prospect enregistré']], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            $message = $e->getMessage();
            return response()->json(['errors' => ['message' => $message]], Response::HTTP_NOT_FOUND);
        }
    }

}
