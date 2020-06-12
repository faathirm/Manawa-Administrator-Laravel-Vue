<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Livestock;

class Customer extends Model
{
    protected $table = 'tb_user';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];

    public function livestock()
    {
        return $this->hasMany('App\Livestock','id_user','id');
    }

}
