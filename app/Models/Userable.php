<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Userable extends Authenticatable
{
    use HasApiTokens;
    protected $table= 'userables';
    protected $primary_key = 'id';

    protected $fillable = [
        'login', 'user_id', 'userable_id', 'userable_type', 'password',
    ];

        /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function userable(){
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }   

    public function oauth_client(){
        return $this->hasOne('App\Models\OauthClient','user_id');
    }

    /** 
     * Find the user instance for the given telephone. 
     *  @param string $username 
     *  @return \App\User 
    */
    public function findForPassport($username) {
        return $this->where('login', $username)->first(); 
    }
}
