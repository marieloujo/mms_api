<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
        
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom','prenom','telephone','adresse','sexe','date_naissance','situation_matrimoniale','prospect', 'actif', 'commune_id','marchand_id','email',
    ];
/* 
    public function marchands()
    {
        return $this->morphedByMany('App\Models\Marchand', 'userable');
    } */


   /*  public function supermarchands()
    {
        return $this->morphedByMany('App\Models\SuperMarchand', 'userable');
    }

    public function beneficiaires()
    {
        return $this->morphedByMany('App\Models\Beneficiaire', 'userable');
    }

    public function directions()
    {
        return $this->morphedByMany('App\Models\Direction', 'userable');
    }

    public function clients()
    {
        return $this->morphedByMany('App\Models\Client', 'userable');
    }

    public function assures()
    {
        return $this->morphedByMany('App\Models\Assurer', 'userable');
    } */

    public function userables()
    {
        return $this->hasMany('App\Models\Userable');
    }

    public function messages(){
        return $this->hasMany('App\Models\Messages');
    }
    
    public function conversations_user(){
        return $this->hasMany('App\Models\ConversationUser');
    }

    public function conversations(){
        return $this->belongsToMany('App\Models\Conversation','conversation_user','user_id','conversation_id')->using('App\Models\ConversationUser')->withPivot([
            'read',
        ])->withTimestamps();
    }

    public function commune(){
        return $this->belongsTo('App\Models\Commune');
    }

    public function marchand(){
        return $this->belongsTo('App\Models\Marchand');
    }
}
