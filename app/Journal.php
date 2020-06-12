<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $table = 'tb_journal';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];

    public function customer()
    {
        return $this->hasOne('App\Customer','id','id_user');
    }

    public function livestock()
    {
        return $this->hasOne('App\Livestock','id_journal_purchase','id');
    }
}
