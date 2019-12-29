<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'commission',
    ];

    public function user()
    {
        return $this->morphOne('App\Models\Userable', 'userable');
    }

    public function userable()
    {
        return Userable::where('userable_id', $this->id)->where('userable_type', 'App\\Models\\Direction')->first();
    }

    public function userable_user()
    {
        return $this->userable()->user;
    }

}
