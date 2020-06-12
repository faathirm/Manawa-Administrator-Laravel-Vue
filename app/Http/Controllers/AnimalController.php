<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Animal;

class AnimalController extends Controller
{
    public function all(Request $request)
    {
        if($request->search == "null" || $request->search == null){
            return Animal::orderBy($request->sort_by, $request->sort_order)->paginate(10);
        }else{
            $query = Animal::orderBy($request->sort_by, $request->sort_order);
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
}
