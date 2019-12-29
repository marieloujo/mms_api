<?php
use Illuminate\Support\Facades\Route;

use App\Models\Departement;
use App\Models\Message;
use App\Notifications\TaskCompleted;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;


//use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user','Api\UserController@getAuthenticatedUserable');

    Route::post('logout','Api\Auth\LoginController@logout');

    Route::post('validation','Api\ContratController@validations');
    Route::get('validationAssurer/{idAssurer}','Api\ContratController@assurerValidation');

    Route::group(['prefix'=>'marchands'],function(){

        Route::get('/{marchand}/clients','Api\MarchandController@getClients');
        Route::get('/{marchand}/prospects','Api\MarchandController@getProspects');
        Route::get('/{marchand}/clients/{client}/contrats','Api\MarchandController@getContrats');
        Route::get('/{marchand}/getWaitingContrats','Api\MarchandController@getWaitingContrats');
        Route::get('/{marchand}/transactions','Api\MarchandController@getTransaction');
        Route::get('/{marchand}/transactions/{date?}','Api\MarchandController@getTransaction');
        Route::get('/{marchand}/getAllTransactions','Api\MarchandController@getTransactions');
        Route::get('/{marchand}/getCompte','Api\MarchandController@getCompte');
        Route::get('/{marchand}/getMarchand','Api\MarchandController@getMarchand');
        Route::get('/{marchand}/getComptes','Api\MarchandController@getComptes');
        Route::get('/{marchand}/getComptes/{date?}','Api\MarchandController@getComptes');
        Route::post('/{marchand}/transfert','Api\CompteController@transfert');

    });
    Route::group(['prefix'=>'supermarchands'],function(){

        Route::get('/{supermarchand}/marchands', 'Api\SuperMarchandController@getMarchands' );
        Route::get('/{supermarchand}/getCompte','Api\SuperMarchandController@getCompte');
        Route::get('/{supermarchand}/getComptes','Api\SuperMarchandController@getComptes');
        Route::get('/{supermarchand}/getComptes/{date?}','Api\SuperMarchandController@getComptes');

    });
    Route::group(['prefix'=>'departements'],function(){
        Route::get('/{departement}/communes/{commune}/marchands','Api\CommuneController@getMarchands');
        Route::get('/{departement}/communes','Api\DepartementController@getCommunes');
    });
    Route::group(['prefix'=>'communes'],function(){
        Route::get('/{commune}/users','Api\CommuneController@getUsers');
        Route::get('/{commune}/departement','Api\CommuneController@getDepartement');
    });
    Route::group(['prefix'=>'clients'],function(){
        Route::get('/{client}/lastContrats','Api\ClientController@getLastContrats');
        Route::get('/{client}/contrats','Api\ClientController@getContrats');
    });
 
    Route::group(['prefix'=>'users'],function() {
        Route::get('/{userPhone}/telephone','Api\UserController@readByPhone');
        Route::get('/{userNom}/nom','Api\UserController@readByNom');
        Route::post('/addProspects', 'Api\UserController@store');
        Route::get('/{user}/conversations','Api\UserController@getDiscussions');
        //Route::get('/{user}/notifications','Api\UserController@notifications');
        Route::get('/{user}/markReadNotifications','Api\UserController@markReadNotifications');
        Route::get('/{user}/unReadNotifications','Api\UserController@unReadNotifications');
        Route::get('/{user}/getNotifications','Api\UserController@getNotifications');
        Route::post('/{user}/notifications','Api\UserController@sendNotifications');
    });


    
Route::post('/notification',function(Request $resquest){
    // when= Carbon::now()->addSeconds(20);
    $message= new Message;
    $message->body=$resquest['body'];
    User::find($resquest['user_id'])->notify(new TaskCompleted($message));
    //return app('UserController')->unReadNotifications($resquest['user_id']);
});


Route::get('validatePhone',function(){
    return response()->json([
        [
            'code'=>'+229',
            'size'=>8,
            'phonePrefix'=>[
                ['num'=>'90'],['num'=>'91'],['num'=>'92'],['num'=>'93'],['num'=>'94'],['num'=>'95'],['num'=>'96'],['num'=>'97'],['num'=>'98'],['num'=>'99'],
                ['num'=>'60'],['num'=>'61'],['num'=>'62'],['num'=>'63'],['num'=>'64'],['num'=>'65'],['num'=>'66'],['num'=>'67'],['num'=>'68'],['num'=>'69']
            
            ]
        ],
        [        
            'code'=>'+225',
            'size'=>8,
            'phonePrefix'=>[
                ['num'=>'01'],['num'=>'02'],['num'=>'03'],['num'=>'04'],['num'=>'05'],['num'=>'06'],['num'=>'07'],['num'=>'08'],['num'=>'09'],
                ['num'=>'84'],['num'=>'85'],['num'=>'86'],['num'=>'87'],['num'=>'88'],['num'=>'89'],
                ['num'=>'71'],['num'=>'72'],['num'=>'73'],['num'=>'74'],['num'=>'75'],['num'=>'76'],['num'=>'77'],['num'=>'78'],['num'=>'79'],
                ['num'=>'51'],['num'=>'52'],['num'=>'53'],['num'=>'54'],['num'=>'55'],['num'=>'56'],['num'=>'57'],['num'=>'58'],['num'=>'59'],
                ['num'=>'40'],['num'=>'41'],['num'=>'42'],['num'=>'43'],['num'=>'44'],['num'=>'45'],['num'=>'44'],['num'=>'47'],['num'=>'48'],['num'=>'49']
            ]
        ]
        
    ]);
});


    Route::group(['prefix'=>'contrats'],function(){
        Route::get('/{refContrat}/contrat','Api\ContratController@showContrat');
    });


    Route::apiResource('marchands','Api\MarchandController');
    Route::apiResource('users','Api\UserController');
    Route::apiResource('clients','Api\ClientController');
    Route::apiResource('documents','Api\DocumentController');
    Route::apiResource('contrats','Api\ContratController');
    Route::apiResource('conversations','Api\ConversationController');
    Route::apiResource('conversations_users','Api\ConversationUserController');
    Route::apiResource('communes','Api\CommuneController');
    Route::apiResource('directions','Api\DirectionController');
    Route::apiResource('departements','Api\DepartementController');
    Route::apiResource('comptes','Api\CompteController');
    Route::apiResource('messages','Api\MessageController');
    Route::apiResource('beneficiaires','Api\BeneficiaireController');
    Route::apiResource('benefices','Api\BeneficeController');
    Route::apiResource('supermarchands','Api\SuperMarchandController');
    Route::apiResource('assures','Api\AssurerController');
    Route::apiResource('portefeuilles','Api\PortefeuilleController');


});
Route::post('login','Api\Auth\LoginController@login');
Route::post('register','Api\Auth\RegisterController@register');
