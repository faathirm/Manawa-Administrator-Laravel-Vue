<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    protected $table = 'tb_farmer';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];

    public function farm()
    {
        return $this->hasOne('App\Farm', 'id','id_farm');
    }
}
