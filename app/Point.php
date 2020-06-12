<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $table = 'tb_point';

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];

    public function customer()
    {
        return $this->hasOne('App\Customer','id','id_user');
    }
}
