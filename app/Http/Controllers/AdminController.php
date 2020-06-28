<?php

namespace App\Http\Controllers;

use App\Admin;
use App\AdminPass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function all(Request $request)
    {
        $admin = Admin::on();
        if($request->search == "null" || $request->search == null){
            return Admin::orderBy($request->sort_by, $request->sort_order)->paginate(10);
        }else{
            $query = Admin::orderBy($request->sort_by, $request->sort_order);
                $columns = ['name','email','created_at'];
            foreach($columns as $column){
                $query->orWhere($column,'LIKE','%'.$request->search.'%');
            }
            $admin = $query->paginate(10);
            return $admin;
        }
    }

    public function new(Request $request)
    {
        $adm = new Admin();
        $adm->email = $request->result['formEmail'];
        $adm->name = $request->result['formName'];
        $adm->added_by = 1;
        $adm->created_at = Date("Y-m-d H:i:s");
        $adm->save();

        $pass = new AdminPass();
        $pass->id_admin = $adm->id;
        $pass->password = Hash::make($request->result['formPassword']);
        $pass->save();

        return $request;
    }

    public function update(Request $request)
    {
        $cust = Admin::find($request->id);
        $cust->name = $request->result['formName'];
        $cust->email = $request->result['formEmail'];
        $cust->save();

        $pass = AdminPass::where('id_admin',$request->id)->first();
        $pass->password = Hash::make($request->result['formPassword']);
        $pass->save();

        return 'Success';
    }

    public function delete(Request $request)
    {
        Admin::destroy($request->result);
        return 'Success';
    }
}
