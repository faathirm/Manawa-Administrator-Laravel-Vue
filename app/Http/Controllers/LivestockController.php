<?php

namespace App\Http\Controllers;

use App\Animal;
use App\Livestock;
use App\LivestockReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LivestockController extends Controller
{
    public function all(Request $request)
    {
        if($request->search == "null" || $request->search == null){
            return Livestock::with('customer')
                ->with('farmvariety.variety.animal')
                ->with('transaction')
                ->with('journal')
                ->with('latestReport')
                ->orderBy($request->sort_by, $request->sort_order)->where('id_journal_purchase','!=',null)->paginate(10);
        }else{
            $query = Livestock::with('customer')
                ->with('farmvariety.variety.animal')
                ->with('transaction')
                ->with('journal')
                ->with('latestReport')
                ->orderBy($request->sort_by, $request->sort_order);
            $columns = ['id, created_at'];
            foreach($columns as $column){
                $query->orWhere($column,'LIKE','%'.$request->search.'%');
            }
            $data = $query->paginate(10);
            return $data;
        }
    }

    public function new(Request $request)
    {
        $ani = new Animal();
        $ani->name = ucwords($request->result['formName']);
        $ani->created_at = date("Y-m-d H:i:s");
        $ani->save();

        return 'Success';
    }

    public function update(Request $request)
    {
        $cust = Animal::find($request->id);
        $cust->name = $request->result['formName'];
        $cust->save();

        return 'Success';
    }

    public function delete(Request $request)
    {
        Animal::Destroy($request->result);
        return 'Success';
    }

    public function detail(Request $request)
    {
        $id = $request->id;
        $data = Livestock::where('id',$id)
            ->with('customer')
            ->with('farmvariety.variety.animal')
            ->with('transaction')
            ->with('journal')
            ->with('report.admin')
            ->with('latestReport')
            ->get();
        return $data;
    }

    public function report (Request $request)
    {
        $l = new LivestockReport();
        $l->id_livestock = $request->id;
        $l->berat = $request->berat;
        $l->kesehatan = $request->kesehatan;
        $l->report_desc = $request->report_desc;
        $file = $request->file('file');
        $file->move("image/report/", date("ymdhis").".".$file->getClientOriginalExtension());
        $l->photo_url = url('/')."/image/report/".date("ymdhis").".".$file->getClientOriginalExtension();
        $l->thumb_url = null;
        $l->reported_by = 1;
        $l->created_at = Date("Y-m-d H:i:s");
        $l->save();

        return 'Success';
    }

    public function reportDelete(Request $request)
    {
        LivestockReport::destroy($request->result);
        return 'Success';
    }
}
