<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Services\PermissionService;
use DB;

class PermissionController extends Controller
{
    private $service;

    function __construct(PermissionService $service)
    {
        
        $this->middleware('role:Admin', ['only' => ['index', 'query_DataTables', 'keep', 'show_PermissionController', 'destroy']]);
        $this->service  = $service;
    }



    public function index(Request $request)
    {

        return view('user.permission');
    }


    public function query_DataTables(Request $request)
    {

        if (request()->ajax()) {

            return  $this->service->find_DataTables($request);
        }
    }

    //função para cadastar e atualizar
    public function keep(Request $request)
    {

        $json  = array();
        $json["status"] = 1;
        $json["error_list"] = array();
        $json["success"] = array();


        if (!$request["id"]) {


            $request = $this->service->store($request);
            $permission = $request['success'] ? $request['data'] : null;

            session()->flash('success', [
                'success'   =>  $request['success'],
                'messages'  =>  $request['messages'],
                'role'      =>  $permission,
            ]);

            if (!$request['success']) {
                $i = 0;
                $json["status"] = 0;
                foreach ($request['messages'] as $msg) {
                    $json["error_list"]["#" . $request['type'][$i]] = $msg;
                    $i++;
                }
            } else {
                $json["success"] = $request['messages'];
            }
        } else {


            $request = $this->service->update($request);
            $permission = $request['success'] ? $request['data'] : null;

            session()->flash('success', [
                'success'   =>  $request['success'],
                'messages'  =>  $request['messages'],
                'role'      =>  $permission,
            ]);

            

            if (!$request['success']) {
                $i = 0;
                $json["status"] = 0;
                foreach ($request['messages'] as $msg) {
                    $json["error_list"]["#" . $request['type'][$i]] = $msg;
                    $i++;
                }
            }
            else {
                $json["success"] = $request['messages'];
            }
        }


        echo json_encode($json);
    }


    //função para retornar funções pelo id
    public function show_PermissionController(Request $request)
    {
        $json  = array();
        $json["status"] = 1;
        $json["imput"] = array();
        $json["permissions"] = array();

        $request        = $this->service->find_Id($request["id"]);
        $permission     = $request['success'] ? $request['data'] : null;
 
        $json["imput"]['id']    = $permission[0]['id'];
        $json["imput"]['name']  = $permission[0]['name'];

        echo json_encode($json);
    }

    //função para deletar funções pelo id
    public function destroy(Request $request)
    {
        $json  = array();
        $json["status"] = 1;
        
        $request = $this->service->delete($request["id"]); 
        $permission = $request['success'] ? $request['data'] : null;

        session()->flash('success', [
            'success'   =>  $request['success'],
            'messages'  =>  $request['messages'],
            'role'      =>  $permission,
        ]);
           
        if(!$request['success']){
            $i=0;
            $json["status"] = 0;
            foreach($request['messages'] as $msg){
            $json["error_list"][$i] = $msg;
            $i++;
            } 
        }else{
            $json["success"] = $request['messages'];

        }
            
        echo json_encode($json);
    }
}
