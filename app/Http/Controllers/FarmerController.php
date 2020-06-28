<?php

namespace App\Http\Controllers;

use App\Farm;
use App\Farmer;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function all(Request $request)
    {
        if($request->search == "null" || $request->search == null){
            return Farmer::with('farm')->orderBy($request->sort_by, $request->sort_order)->paginate(10);
        }else{
            $query = Farmer::with('farm')->orderBy($request->sort_by, $request->sort_order);
            $columns = ['name','email'];
            foreach($columns as $column){
                $query->orWhere($column,'LIKE','%'.$request->search.'%');
            }
            $data = $query->paginate(10);
            return $data;
        }
    }

    public function new(Request $request)
    {
        $far = new Farm();
        $far->name = $request->result['formFarmName'];
        $far->address = $request->result['formAddress'];
        $far->city = $request->result['formCity'];
        $far->phone_num = $request->result['formPhone'];
        $far->email = $request->result['formEmail'];
        $far->created_at = Date('Y-m-d H:i:s');
        $far->save();

        $mer = new Farmer();
        $mer->id_farm = $far->id;
        $mer->email = $request->result['formEmail'];
        $mer->name = $request->result['formName'];
        $mer->phone_num = $request->result['formPhone'];
        $mer->save();

        return 'Success';
    }

    public function update(Request $request)
    {
        $far = Farm::find($request->id);
        $far->name = $request->result['formFarmName'];
        $far->address = $request->result['formAddress'];
        $far->city = $request->result['formCity'];
        $far->phone_num = $request->result['formPhone'];
        $far->email = $request->result['formEmail'];
        $far->save();

        $mer = Farmer::find($request->farmerId);
        $mer->id_farm = $far->id;
        $mer->email = $request->result['formEmail'];
        $mer->name = $request->result['formName'];
        $mer->phone_num = $request->result['formPhone'];
        $mer->save();

        return 'Success';
    }

    public function delete(Request $request)
    {
        Farmer::destroy($request->result);
        Farm::destroy($request->farm);
        return 'Success';
    }
}
