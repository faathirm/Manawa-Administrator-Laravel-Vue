<?php

namespace App\Http\Controllers;

use App\Confirmation;
use App\Point;
use Illuminate\Http\Request;

class ConfirmationController extends Controller
{
    public function all(Request $request)
    {
        if($request->search == "null" || $request->search == null){
            return Confirmation::with('transaction.customer')
                ->with('useraccount.customer')
                ->with('manawaaccount')
                ->with('journal')
                ->with('admin')
                ->orderBy($request->sort_by, $request->sort_order)->paginate(10);
        }else{
            $query = Confirmation::with('transaction.customer')
                ->with('useraccount.customer')
                ->with('manawaaccount')
                ->with('journal')
                ->with('admin')
                ->orderBy($request->sort_by, $request->sort_order);
            $columns = ['id','created_at'];
            foreach($columns as $column){
                $query->orWhere($column,'LIKE','%'.$request->search.'%');
            }
            $data = $query->paginate(10);
            return $data;
        }
    }

    public function update(Request $request)
    {
        $conf = Confirmation::find($request->id);
        if($request->status == 2){
            $conf->verified_by = 1;
            $conf->verified_at = Date('Y-m-d H:i:s');
            $conf->denied_by = null;
            $conf->denied_at = null;

            $point = new Point();
            $point->id_user = $conf->transaction->customer->id;
            $point->jumlah = 10;
            $point->status = 1;
            $point->deskripsi = "Pembelian Hewan Ternak";
            $point->created_at = Date("Y-m-d H:i:s");
            $point->updated_at = Date("Y-m-d H:i:s");
            $point->save();

        }else if($request->status == 3){
            $conf->denied_by = 1;
            $conf->denied_at = Date('Y-m-d H:i:s');
            $conf->verified_by = null;
            $conf->verified_at = null;
        }else{
            $conf->verified_by = null;
            $conf->denied_by = null;
            $conf->verified_at = null;
            $conf->denied_at = null;
        }
        $conf->save();
        return "Success";
    }

    public function delete(Request $request)
    {
        Confirmation::destroy($request->result);
        return 'Success';
    }
}
