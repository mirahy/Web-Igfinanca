<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\RoleService;
use DB;

class RoleController extends Controller
{
    private $service;

    function __construct(RoleService $service)
    {
        $this->middleware('permission:role-list', ['only' => ['index']]);
        // $this->middleware('permission:role-create', ['only' => ['query_DataTables','keep']]);
        // $this->middleware('permission:role-edit', ['only' => ['show_roles','keep', 'query_DataTables']]);
        // $this->middleware('permission:role-delete', ['only' => ['destroy', 'query_DataTables']]);
        $this->service  = $service;
    }



    public function index(Request $request)
    {

        $permission = Permission::get();
        return view('user.roles', compact('permission'));
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
            $role = $request['success'] ? $request['data'] : null;

            session()->flash('success', [
                'success'   =>  $request['success'],
                'messages'  =>  $request['messages'],
                'role'      =>  $role,
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
            $role = $request['success'] ? $request['data'] : null;

            session()->flash('success', [
                'success'   =>  $request['success'],
                'messages'  =>  $request['messages'],
                'role'      =>  $role,
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
    public function show_roles(Request $request)
    {
        $json  = array();
        $json["status"] = 1;
        $json["imput"] = array();
        $json["permissions"] = array();


        $request        = $this->service->find_Id($request["id"]);
        $roles          = $request['success'] ? $request['data'] : null;
        $permission     = $request['success'] ? $request['data2'] : null;

        
        $json["imput"]['id']    = $roles[0]['id'];
        $json["imput"]['name']  = $roles[0]['name'];
        $json["permissions"]    = $permission;


        echo json_encode($json);
    }

    //função para deletar funções pelo id
    public function destroy(Request $request)
    {
        $json  = array();
        $json["status"] = 1;
        
        $request = $this->service->delete($request["id"]); 
        $role = $request['success'] ? $request['data'] : null;

        session()->flash('success', [
            'success'   =>  $request['success'],
            'messages'  =>  $request['messages'],
            'role'      =>  $role,
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
