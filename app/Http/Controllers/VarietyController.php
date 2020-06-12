<?php

namespace App\Http\Controllers;

use App\Animal;
use App\Variety;
use Illuminate\Http\Request;

class VarietyController extends Controller
{
    public function all(Request $request)
    {
        if($request->search == "null" || $request->search == null){
            return Variety::with('animal')->orderBy($request->sort_by, $request->sort_order)->paginate(10);
        }else{
            $query = Variety::with('animal')->orderBy($request->sort_by, $request->sort_order);
            $columns = ['name','created_at'];
            foreach($columns as $column){
                $query->orWhere($column,'LIKE','%'.$request->search.'%');
            }
            $data = $query->paginate(10);
            return $data;
        }
    }

    public function new(Request $request)
    {
        $ani = new Variety();
        $ani->id_animal = ucwords($request->result['formName']);
        $ani->name = ucwords($request->result['formVariety']);
        $ani->created_at = date("Y-m-d H:i:s");
        $ani->save();

        return 'Success';
    }

    public function update(Request $request)
    {
        $cust = Variety::find($request->id);
        $cust->id_animal = $request->result['formName'];
        $cust->name = $request->result['formVariety'];
        $cust->save();

        return 'Success';
    }

    public function delete(Request $request)
    {
        Variety::Destroy($request->result);
        return 'Success';
    }

    public function animal(Request $request)
    {
        return Animal::all();
    }
}
