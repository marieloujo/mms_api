<?php

namespace App\Models;
use App\Models\Userable;
use Illuminate\Database\Eloquent\Model;

class Beneficiaire extends Model
{

    public $incrementing=true;
    public $table='beneficiaires';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
    ];
    public function user()
    {
        return $this->morphOne('App\Models\Userable', 'userable');
    }

    public function userable()
    {
        return Userable::where('userable_id', $this->id)->where('userable_type', 'App\\Models\\Beneficiaire')->first();
    }

    public function userable_user()
    {
        return $this->userable()->user;
    }


    public function benefices(){
        return $this->hasMany('App\Models\Benefice');
    }

    public function contrats(){
        return $this->belongsToMany('App\Models\Contrat','benefices','beneficiaire_id','contrat_id')->using('App\Models\Benefice')->withPivot([
            'statut','taux',
        ])->withTimestamps();
    }

}
