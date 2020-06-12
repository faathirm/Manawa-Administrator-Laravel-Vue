<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Livestock extends Model
{
    protected $table = 'tb_livestock';
    public $timestamps = false;

    public function customer(){
        return $this->hasOne('App\Customer','id','id_user');
    }

    public function farmvariety(){
        return $this->hasOne('App\FarmVariety','id','id_farm_variety');
    }

    public function transaction(){
        return $this->hasOne('App\Transaction','id','id_transaction');
    }

    public function journal(){
        return $this->hasOne('App\Journal','id','id_journal_purchase');
    }

    public function report(){
        return $this->hasMany('App\LivestockReport','id_livestock','id')->orderBy('id','desc');
    }

    public function latestReport(){
        return $this->hasOne('App\LivestockReport','id_livestock','id')->latest();
    }

}
