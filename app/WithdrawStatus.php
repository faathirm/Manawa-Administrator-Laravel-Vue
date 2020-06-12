<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WithdrawStatus extends Model
{
    protected $table = 'tb_withdraw_status';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];
}
