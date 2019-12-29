<?php

namespace App\Models;
use App\Models\Userable;
use Illuminate\Database\Eloquent\Model;

class Assurer extends Model
{

    public $table='assures';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'etat','profession',
        'employeur' ,
    ];
    public function user()
    {
        return $this->morphOne('App\Models\Userable', 'userable');
    }

    public function userable()
    {
        return Userable::where('userable_id', $this->id)->where('userable_type', 'App\\Models\\Assurer')->first();
    }

    public function userable_user()
    {
        return $this->userable()->user;
    }

    public function contrats(){
        return $this->hasMany('App\Models\Contrat');
    }

 
}
