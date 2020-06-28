<?php

namespace App\Http\Controllers;

use App\Ticketing;
use Illuminate\Http\Request;

class TicketingController extends Controller
{
    public function all(Request $request)
    {
        if($request->search == "null" || $request->search == null){
            return Ticketing::with('customer')
                ->orderBy($request->sort_by, $request->sort_order)->paginate(10);
        }else{
            $query = Ticketing::with('customer')
                ->orderBy($request->sort_by, $request->sort_order);
            $columns = ['id','category','status','created_at'];
            foreach($columns as $column){
                $query->orWhere($column,'LIKE','%'.$request->search.'%');
            }
            $data = $query->paginate(10);
            return $data;
        }
    }

    public function new(Request $request)
    {
        $i = new Ticketing();
        $i->id_user = $request->id_user;
        $i->category = $request->category;
        $i->status = "Process";
        $i->message = $request->message;
        $i->save();
        return "Success";
    }

    public function update(Request $request)
    {
        $i = Ticketing::find($request->id);
        $i->status = $request->status;
        $i->save();
        return $request->status;
    }

    public function delete(Request $request)
    {
        Ticketing::destroy($request->result);
        return 'Success';
    }
}
