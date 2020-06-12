<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'tb_transaction';
    public $timestamps = false;

    public function customer()
    {
        return $this->hasOne('App\Customer','id','id_user');
    }
}
