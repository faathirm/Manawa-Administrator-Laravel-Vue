<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManawaAccount extends Model
{
    protected $table = 'tb_manawa_acc';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];
}
