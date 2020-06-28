<?php

namespace App\Http\Controllers;

use App\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function all(Request $request)
    {
        if($request->search == "null" || $request->search == null){
            return Voucher::with('customer')->orderBy($request->sort_by, $request->sort_order)->paginate(10);
        }else{
            $query = Voucher::with('customer')->orderBy($request->sort_by, $request->sort_order);
            $columns = ['available_at','expired_at','amount','code'];
            foreach($columns as $column){
                $query->orWhere($column,'LIKE','%'.$request->search.'%');
            }
            $data = $query->paginate(10);
            return $data;
        }
    }

    public function new(Request $request)
    {
        $ani = new Voucher();
        $ani->id_user = null;
        $ani->code = $request->result["formCode"];
        $ani->description = $request->result["formDescription"];
        $ani->amount = $request->result["formAmount"];
        $ani->max_usage = $request->result["formMaxUsage"];
        $ani->available_at = date("Y-m-d",strtotime($request->available))." 00:00:00";
        $ani->expired_at = date("Y-m-d",strtotime($request->expired))." 23:59:59";
        $ani->created_at = date("Y-m-d H:i:s");
        $ani->save();

        return 'Success';
    }

    public function update(Request $request)
    {
        $cust = Voucher::find($request->id);
        $cust->id_user = null;
        $cust->code = $request->result["formCode"];
        $cust->description = $request->result["formDescription"];
        $cust->amount = $request->result["formAmount"];
        $cust->max_usage = $request->result["formMaxUsage"];
        $cust->available_at = date("Y-m-d",strtotime($request->available))." 00:00:00";
        $cust->expired_at = date("Y-m-d",strtotime($request->expired))." 23:59:59";
        $cust->created_at = date("Y-m-d H:i:s");
        $cust->save();

        return 'Success';
    }

    public function delete(Request $request)
    {
        Voucher::Destroy($request->result);
        return 'Success';
    }
}
