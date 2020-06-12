<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HelpArticle extends Model
{
    protected $table = 'tb_help_article';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'date:Y-m-d H:i:s'
    ];

    public function category()
    {
        return $this->hasOne('App\HelpCategory','id','id_help_category');
    }

    public function status()
    {
        return $this->hasOne('App\HelpStatus','id_help_article','id');
    }
}
