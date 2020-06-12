<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LivestockReport extends Model
{
    protected $table = 'tb_livestock_report';
    public $timestamps = false;

    public function admin(){
        return $this->hasOne('App\Admin','id','reported_by');
    }
}
