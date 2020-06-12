<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'tb_admin';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];

}
