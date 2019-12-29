<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperMarchand extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'matricule','commission',
    ];

    public function user()
    {
        return $this->morphOne('App\Models\Userable', 'userable');
    }

    public function userable()
    {
        return Userable::where('userable_id', $this->id)->where('userable_type', 'App\\Models\\SuperMarchand')->first();
    }

    public function userable_user()
    {
        return $this->userable()->user; 
    }
    
    public function comptes(){
        return $this->morphMany('App\Models\Compte','compteable');
    }
    public function marchands(){
        return $this->hasMany('App\Models\Marchand');
    }
}
