<?php

namespace App\Http\Controllers;

use App\Journal;
use App\LivestockReport;
use App\Point;
use App\Voucher;
use Illuminate\Http\Request;
use App\Customer;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function all(Request $request)
    {
        if($request->search == "null" || $request->search == null){
            $query = Customer::orderBy($request->sort_by, $request->sort_order);
        }else{
            $query = Customer::orderBy($request->sort_by, $request->sort_order);
            $columns = ['name','email','created_at'];
            foreach($columns as $column){
                $query->orWhere($column,'LIKE','%'.$request->search.'%');
            }
        }
        $admin = $query->paginate(10);
        return $admin;
    }

    public function new(Request $request)
    {
        return $request;
    }

    public function update(Request $request)
    {
        $cust = Customer::find($request->id);
        $cust->name = $request->result['formName'];
        $cust->save();

        return 'Success';
    }

    public function delete(Request $request)
    {
        Customer::destroy($request->result);
        return 'Success';
    }

    public function detail(Request $request)
    {
        $id = $request->id;
        $data = Customer::where('id',$id)->select('id','email','name','created_at')->first();

        $latestReport = DB::table('tb_livestock_report')
            ->select('*')
            ->orderByDesc('created_at')
            ->groupBy('id_livestock');

        $livestocks = DB::table('tb_livestock as live')
            ->leftJoinSub($latestReport, 'report','live.id','=','report.id_livestock')
            ->leftJoin('tb_farm_variety as fv','live.id_farm_variety','=','fv.id')
            ->leftJoin('tb_variety as v','fv.id_variety','=','v.id')
            ->leftJoin('tb_animal as a','v.id_animal','=','a.id')
            ->where('live.id_user','=',$id)
            ->where('live.id_journal_purchase','!=',null)
            ->select(
                'live.id as id',
                'a.name as animalName',
                'v.name as varietyName',
                'live.born_at as bornAt',
                'fv.sales_type as type',
                'report.kesehatan'
            )
            ->get();

//        $transactions = DB::table('tb_journal')
//            ->where('id_user','=',$id)
//            ->orderBy('id','desc')
//            ->get();
        $transactions = Journal::where('id_user',$id)->get();

        $balance = DB::table('tb_journal as j')
            ->where('id_user','=',$id)
            ->sum('value');

        $livestock_amount = DB::table('tb_livestock as live')
            ->where('live.id_user','=',$id)
            ->where('live.id_journal_purchase','!=',null)
            ->count('id');

        $transaction_amount = DB::table('tb_journal as j')
            ->where('id_user','=',$id)
            ->count('id');

        $pending_amount = DB::table('tb_transaction')
            ->where('id_user','=',$id)
            ->where('status','!=','Transaksi Berhasil')
            ->count('id');

        $data['balance'] = "Rp. ".format_uang($balance);
        $data['transactionAmount'] = $transaction_amount;
        $data['livestockAmount'] = $livestock_amount;
        $data['pendingAmount'] = $pending_amount;
        $data['livestocks'] = $livestocks;
        $data['transactions'] = $transactions;

        return $data->toJson();
    }

    public function getReward($iduser)
    {
        $poi_plus = Point::with('customer')->where('id_user',$iduser)->where('status',1)->sum('jumlah');
        $poi_min = Point::with('customer')->where('id_user',$iduser)->where('status',0)->sum('jumlah');
        $user = Customer::find($iduser);
        $user['jumlah'] = $poi_plus-$poi_min;
        return $user;
    }

    public function addVoucher(Request $request)
    {
        $poi = new Point();
        $poi->id_user = $request->id;
        $poi->jumlah = 100;
        $poi->status = 0;
        $poi->deskripsi = "Klaim voucher rewardku";
        $poi->save();

        $vou = new Voucher();
        $vou->id_user = $request->id;
        $vou->code = "rewardku";
        $vou->description = null;
        $vou->amount = 100000;
        $vou->max_usage = 1;
        $vou->used = 0;
        $vou->available_at = Date('Y-m-d H:i:s');
        $vou->expired_at = strtotime("+7 day").' 23:59:59';
        $vou->created_at = Date("Y-m-d H:i:s");
        $vou->save();
        return $request->id;
    }

    public function allPoint($id)
    {
        $poi = Point::where('id_user',$id)->get();
        return $poi;
    }
}
