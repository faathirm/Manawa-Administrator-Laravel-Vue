<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FarmVariety extends Model
{
    protected $table = 'tb_farm_variety';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];

    public function farm()
    {
        return $this->hasOne('App\Farm','id','id_farm');
    }

    public function variety()
    {
        return $this->hasOne('App\Variety','id','id_variety');
    }

}
