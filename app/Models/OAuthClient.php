<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthClient extends Model
{
    protected $table = "oauth_clients";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','user_id', 'name', 'secret','redirect', 'personal_access_client', 'password_client','revoked',
    ];

    public function log(){
        return $this->belongsTo('App\Models\Log','user_id');
    }

}
