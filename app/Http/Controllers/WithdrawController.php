<?php

namespace App\Http\Controllers;

use App\Withdraw;
use App\WithdrawStatus;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function all(Request $request)
    {
        if($request->search == "null" || $request->search == null){
            return Withdraw::with('journalSent')
                ->with('journalReturn')
                ->with('customer')
                ->with('customerAccount.customer')
                ->with('withdrawStatus')
                ->orderBy($request->sort_by, $request->sort_order)->paginate(10);
        }else{
            $query = Withdraw::with('journalSent')
                ->with('journalReturn')
                ->with('customer')
                ->with('customerAccount.customer')
                ->with('withdrawStatus')
                ->orderBy($request->sort_by, $request->sort_order);
            $columns = ['amount','id','created_at'];
            foreach($columns as $column){
                $query->orWhere($column,'LIKE','%'.$request->search.'%');
            }
            $admin = $query->paginate(10);
            return $admin;
        }
    }

    public function update(Request $request)
    {

        $s = new WithdrawStatus();
        $s->id_withdraw = $request->id;
        $s->id_admin = 1;
        $s->status = 'Selesai';
        $file = $request->file('file');
        $file->move("image/withdraw/", date("ymdhis").".".$file->getClientOriginalExtension());
        $s->photo_url = url('/')."/image/withdraw/".date("ymdhis").".".$file->getClientOriginalExtension();
        $s->created_at = date('Y-m-d H:i:s');
        $s->save();

        return "Success";
//
//        $image = $request->file('file');
//        return $image->getClientOriginalExtension();
//        return $image->getClientOriginalExtension();
    }

    public function decline(Request $request)
    {
        $s = new WithdrawStatus();
        $s->id_withdraw = $request->id;
        $s->id_admin = 1;
        $s->status = 'Dibatalkan';
        $s->photo_url = null;
        $s->created_at = date('Y-m-d H:i:s');
        $s->save();

        return "Success";
    }

    public function delete(Request $request)
    {
        $deleteStatus = WithdrawStatus::where('id_withdraw',$request->id)->delete();
        Withdraw::destroy($request->id);

        return 'Success';
    }
}
