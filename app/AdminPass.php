<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminPass extends Model
{
    protected $table = 'tb_admin_pass';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];
}
