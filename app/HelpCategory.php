<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HelpCategory extends Model
{
    protected $table = 'tb_help_category';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];
}
