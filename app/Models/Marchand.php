<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Userable;
class Marchand extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'matricule','commission','credit_virtuel','super_marchand_id',
    ];
    
    public function user(){
        return $this->morphOne('App\Models\Userable', 'userable');
    }

    public function super_marchand(){
        return $this->belongsTo('App\Models\SuperMarchand','super_marchand_id');
    }
    
    public function comptes(){
        return $this->morphMany('App\Models\Compte','compteable');
    }

    public function contrats(){
        return $this->hasMany('App\Models\Contrat');
    }

    public function portefeuilles(){
        return $this->hasMany('App\Models\Portefeuille');
    }

    public function prospects(){
        return $this->hasMany('App\User','marchand_id');
    }

    public function clients(){
        return $this->belongsToMany('App\Models\Client','contrats','marchand_id','client_id')->using('App\Models\Contrat')->withPivot([
            'numero_contrat','garantie','prime','duree','numero_police_assurance','date_debut','date_echeance','date_effet','fin','valider','assure_id',
        ])->withTimestamps();
    }

    public function userable(){
        return Userable::where('userable_id',$this->id)->where('userable_type', 'App\\Models\\Marchand')->first();
    }

    public function userable_user(){
        return $this->userable()->user;
    }

}
