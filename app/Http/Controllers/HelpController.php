<?php

namespace App\Http\Controllers;

use App\HelpArticle;
use App\HelpCategory;
use App\HelpStatus;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function categoryList()
    {
        $cat = HelpCategory::all();
        return $cat;
    }

    public function articleList($id)
    {
        $art = HelpArticle::with('category')->where('id_help_category',$id)->get();
        return $art;
    }

    public function articleDetail($id,$iduser)
    {
        $art = HelpArticle::where('id',$id)->get();
        $sta = HelpStatus::with('customer')->where('id_help_article',$id)->where('id_user',$iduser)->orderBy('id','desc')->first();
        $art[0]["status"] = $sta;

        return $art;
    }

    public function like(Request $request){
        $sta = new HelpStatus();
        $sta->id_help_article = $request['id_article'];
        $sta->id_user = $request['id'];
        $sta->status = $request['status'];
        $sta->save();
        return $request['id_article'];
    }
}
