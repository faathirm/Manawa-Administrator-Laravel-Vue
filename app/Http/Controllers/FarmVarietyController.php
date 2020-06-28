<?php

namespace App\Http\Controllers;

use App\Farm;
use App\FarmVariety;
use App\Variety;
use Illuminate\Http\Request;

class FarmVarietyController extends Controller
{
    public function all(Request $request)
    {
        if($request->search == "null" || $request->search == null){
            return FarmVariety::with('farm')
                ->with('variety')
                ->with('variety.animal')
                ->orderBy($request->sort_by, $request->sort_order)->paginate(10);
        }else{
            $query = FarmVariety::with('farm')
                ->with('variety')
                ->with('variety.animal')
                ->orderBy($request->sort_by, $request->sort_order);
            $columns = ['sales_type','created_at'];
            foreach($columns as $column){
                $query->orWhere($column,'LIKE','%'.$request->search.'%');
            }
            $data = $query->paginate(10);
            return $data;
        }
    }

    public function new(Request $request)
    {
        $ani = new FarmVariety();
        $ani->id_farm = $request->farm;
        $ani->id_variety = $request->variety;
        $ani->sales_type = $request->sales_type;
        $ani->price_base = $request->price_base;
        $ani->price_monthly_incr = $request->price_monthly_incr;
        $ani->price_insurance = $request->price_insurance;
        $ani->price_est_sell = $request->price_est_sell;
        $ani->variety_desc = $request->variety_desc;
        $file = $request->file('file');
        $file->move("image/product/", date("ymdhis").".".$file->getClientOriginalExtension());
        $ani->photo_url = url('/')."/image/product/".date("ymdhis").".".$file->getClientOriginalExtension();
        $ani->thumbnail_url = url('/')."/image/product/".date("ymdhis").".".$file->getClientOriginalExtension();
        $ani->stock = $request->stock;
        $ani->created_at = Date("Y-m-d H:i:s");
        $ani->save();
        return 'Success';
    }

    public function update(Request $request)
    {
        $ani = FarmVariety::find($request->id);
        $ani->id_farm = $request->farm;
        $ani->id_variety = $request->variety;
        $ani->sales_type = $request->sales_type;
        $ani->price_base = $request->price_base;
        $ani->price_monthly_incr = $request->price_monthly_incr;
        $ani->price_insurance = $request->price_insurance;
        $ani->price_est_sell = $request->price_est_sell;
        $ani->variety_desc = $request->variety_desc;
        $file = $request->file('file');
        $file->move("image/product/", date("ymdhis").".".$file->getClientOriginalExtension());
        $ani->photo_url = url('/')."/image/product/".date("ymdhis").".".$file->getClientOriginalExtension();
        $ani->thumbnail_url = url('/')."/image/product/".date("ymdhis").".".$file->getClientOriginalExtension();
        $ani->stock = $request->stock;
        $ani->save();
        return 'Success';
    }

    public function delete(Request $request)
    {
        FarmVariety::Destroy($request->result);
        return 'Success';
//        return $request;
    }

    public function allFarm()
    {
        return Farm::all();
    }

    public function allVariety()
    {
        return Variety::with('animal')->get();
    }

}
