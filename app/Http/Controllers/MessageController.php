<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{

    

    public function messageReturn($request)
    {

        $json  = array();
        $json["status"] = 1;
        $json["error_list"] = array();
        $json["success"] = array();

        if(!$request['success']){
            $i=0;
            $json["status"] = 0;
              foreach($request['messages'] as $msg){
                  $json["error_list"]["#".$request['type'][$i]] = $msg;
                  $i++;
              } 
            } else{
                $json["success"] = $request['messages'];
    
            }

            return $json;
    }
}
