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
//        $ani = new FarmVariety();
//        $ani->id_animal = ucwords($request->result['formName']);
//        $ani->name = ucwords($request->result['formVariety']);
//        $ani->created_at = date("Y-m-d H:i:s");
//        $ani->save();

        return $request;
    }

    public function update(Request $request)
    {
        $cust = FarmVariety::find($request->id);
        $cust->id_animal = $request->result['formName'];
        $cust->name = $request->result['formVariety'];
        $cust->save();

        return 'Success';
    }

    public function delete(Request $request)
    {
        FarmVariety::Destroy($request->result);
        return 'Success';
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
