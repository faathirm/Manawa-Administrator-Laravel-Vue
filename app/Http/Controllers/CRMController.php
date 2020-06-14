<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\Notification as Notification;
use App\Notifications\ManawaNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CRMController extends Controller
{
    public function post(Request $request)
    {
        $data[0] = ['nama'=>'Faathir','email'=>'faathir.muhammad@gmail.com'];
        $data[1] = ['nama'=>'Isye','email'=>'isyeadhiwi75@gmail.com'];
//        $data[2] = ['nama'=>'Dendy','email'=>'dendy.aziz@gmail.com'];
        for($x=0;$x<2;$x++){
            $details = [
                'greeting' => 'Hai, '.$data[$x]["nama"],
                'body' => $request->message,
            ];

            \Illuminate\Support\Facades\Notification::route('mail', $data[$x]["email"])->notify(new ManawaNotification($details));
        }
        return "Email terkirim";
    }

    public function test(){
//        $data[0]["nama"] = "Faathir Muhammad";
//        $data[0]["nama"] = "Faathir Muhammad";
        $data[0] = ['nama'=>'Faathir Muhammad','email'=>'faathir.muhammad@gmail.com'];
        $data[1] = ['nama'=>'Isye S. Adhiwinaya','email'=>'isyeadhiwi75@gmail.com'];
//        $data[1]["email"] = "isyeadhiwi75@gmail.com";
//        $data[1]["email"] = "isyeadhiwi75@gmail.com";
        for($x=0;$x<2;$x++){
            echo $data[$x]["nama"];
        }
    }
}
