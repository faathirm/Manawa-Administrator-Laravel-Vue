<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    protected $table = 'tb_user_acc';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];

    public function customer()
    {
        return $this->hasMany('App\Customer','id','id_user');
    }
}
