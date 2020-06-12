<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HelpStatus extends Model
{
    protected $table = 'tb_help_status';

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];

    public function customer()
    {
        return $this->hasOne('App\Customer','id','id_user');
    }
}
