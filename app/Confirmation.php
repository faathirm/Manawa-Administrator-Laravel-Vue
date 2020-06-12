<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Confirmation extends Model
{
    protected $table = 'tb_transaction_conf';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];

    public function transaction()
    {
        return $this->hasOne('App\Transaction','id','id_transaction');
    }

    public function useraccount()
    {
        return $this->hasOne('App\UserAccount','id','id_user_acc');
    }

    public function journal()
    {
        return $this->hasOne('App\Journal','id','id_journal_topup');
    }

    public function manawaaccount()
    {
        return $this->hasOne('App\ManawaAccount','id','id_manawa_acc');
    }

    public function admin()
    {
        return $this->hasOne('App\Admin','id','verified_by');
    }

}
