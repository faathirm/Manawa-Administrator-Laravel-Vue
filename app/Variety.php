<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variety extends Model
{
    protected $table = 'tb_variety';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];

    public function animal()
    {
        return $this->hasOne('App\Animal','id','id_animal');
    }
}
