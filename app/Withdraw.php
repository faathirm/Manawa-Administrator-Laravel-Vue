<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $table = 'tb_withdraw';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];

    public function journalSent()
    {
        return $this->hasOne('App\Journal','id','id_journal_return');
    }

    public function journalReturn()
    {
        return $this->hasOne('App\Journal','id','id_journal_return');
    }

    public function customer()
    {
        return $this->hasOne('App\Customer','id','id_user');
    }

    public function customerAccount()
    {
        return $this->hasOne('App\UserAccount','id','id_user_acc');
    }

    public function withdrawStatus()
    {
        return $this->hasOne('App\WithdrawStatus','id_withdraw','id')->latest();
    }
}
