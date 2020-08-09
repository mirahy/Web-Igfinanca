<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Entities\TbLaunch;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\TbLaunchCreateRequest;
use App\Http\Requests\TbLaunchUpdateRequest;
use App\Repositories\TbLaunchRepository;
use App\Repositories\TbCaixaRepository;
use App\Validators\TbLaunchValidator;
use Yajra\Datatables\Datatables;
use App\Services\TbLaunchService;

const CONSTANT_MES = ['','JANEIRO', 'FEVEREIRO', 'MARÇO', 'ABRIL','MAIO','JUNHO','JULHO','AGOSTO','SETEMBRO','OUTUBRO','NOVEMBRO','DEZENBRO'];

/**
 * Class TbLaunchesController.
 *
 * @package namespace App\Http\Controllers;
 */
class TbLaunchController extends Controller
{

    protected $TbCaixaRepository;
    protected $repository; 
    protected $service;
    

   
    public function __construct(TbLaunchRepository $repository, TbLaunchService $service, TbCaixaRepository $TbCaixaRepository)
    {
        $this->TbCaixaRepository  = $TbCaixaRepository;
        $this->repository         = $repository;
        $this->service            = $service;
    }

    
    public function index()
    {

       
        
        $caixa_list  = $this->TbCaixaRepository->selectBoxList();

        
        return view('launch.launchs_e', [
            'year'         => date("Y"),
            'operation'    => 0,
            'type_launch'  => 0,
            'base'         => 0,
            'closing'      => 0,
            'status'       => 0,
            'id_user'      => 0,
            'caixa_list'   => $caixa_list,
            'data'         => CONSTANT_MES,
        ]);
    }

    public function index_s()
    {

        $caixa_list  = $this->TbCaixaRepository->selectBoxList();
        
        return view('launch.launchs_s', [
            'year'         => date("Y"),
            'operation'    => 0,
            'type_launch'  => 0,
            'base'         => 0,
            'closing'      => 0,
            'status'       => 0,
            'caixa_list'   => $caixa_list,
            'id_user'      => 0,
            'data'         => CONSTANT_MES,
        ]);
    }

    public function index_l()
    {
        
        return view('launch.launchs_apr', [
            'year'         => date("Y"),
            'operation'    => 0,
            'type_launch'  => 0,
            'base'         => 0,
            'closing'      => 0,
            'status'       => 0,
            'id_user'      => 0,
            'data'         => CONSTANT_MES,
        ]);
    }

    Public function query(Request $request){
        
        if(request()->ajax()){

           $def = '%';

            return Datatables::of(TbLaunch::query()
                                    ->with('user')
                                    ->with('type_launch')
                                    ->where([['idtb_type_launch', 'LIKE', $request->query('launch', $def)],['status', 'LIKE', $request->query('status', $def) ]]))
                                    ->blacklist(['action'])
                                    ->make(true);
        }
           

    }



    public function keep(Request $request)
    {
        $json  = array();
        $json["status"] = 1;
        $json["error_list"] = array();
        $json["success"] = array();

        $id = $this->service->find_IdUser($request['name'])->toArray();

        if($id){
            $request['id_user'] = $id[0]['id'];

        }else{
            $json["status"] = 0;
            $json["error_list"]["#name"] = "Usuário não cadastrado";

            echo json_encode($json);
            return;

        }

  
        if(!$request["id"]){
            
            $request = $this->service->store($request->all()); 
            $launch = $request['success'] ? $request['data'] : null;
           
           
               
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
            

        }else{
            
            
            $request = $this->service->update($request->all()); 
            $launch = $request['success'] ? $request['data'] : null;

             
             if(!$request['success']){
                $i=0;
                $json["status"] = 0;
                  foreach($request['messages'] as $msg){
                    $json["error_list"]["#".$request['type'][$i]] = $msg;
                      $i++;
                  } 
                }else{
                    $json["success"] = $request['messages'];

                }      
            
        }
       
         
         
             echo json_encode($json);
    }

    public function show_launch(Request $request)
    {
        $json  = array();
        $json["status"] = 1;
        $json["imput"] = array();
        

        $request = $this->service->find_Id($request["id"]); 
        $launch = $request['success'] ? $request['data'] : null;
        
        $json["imput"]['id'] = $launch['id'];
        $json["imput"]['id_user'] = $launch['id_user'];
        $json["imput"]['value'] = $launch['value'];
        $json["imput"]['operation_date'] = $launch['operation_date'];
        $json["imput"]['reference_month'] = $launch['reference_month'];
        $json["imput"]['reference_year'] = $launch['reference_year'];
        $json["imput"]['idtb_operation'] = $launch['idtb_operation'];
        $json["imput"]['idtb_type_launch'] = $launch['idtb_type_launch'];
        $json["imput"]['idtb_base'] = $launch['idtb_base'];
        $json["imput"]['status'] = 0;
        $json["imput"]['idtb_closing'] = $launch['idtb_closing'];
        $json["imput"]['name'] = $launch['user']['name'];

        echo json_encode($json);

    }


    public function destroy(Request $request)
    {
        $json  = array();
        $json["status"] = 1;
        
        $request = $this->service->delete($request["id"]); 
        $launch = $request['success'] ? $request['data'] : null;

        session()->flash('success', [
            'success'   =>  $request['success'],
            'messages'  =>  $request['messages'],
            'launch'   =>  $launch,
        ]);
           
        if(!$request['success']){
            $i=0;
            $json["status"] = 0;
            foreach($request['messages'] as $msg){
            $json["error_list"]["#".$request['type'][$i]] = $msg;
            $i++;
            } 
        }else{
            $json["success"] = $request['messages'];

        }
            
        echo json_encode($json);
    }

    public function aprov(Request $request)
    {

        $request = $this->service->aprov($request->all()); 

        $json["success"] = $request['messages'];
        $json["status"] = $request['success'];
              
        echo json_encode($json);

    }

  
}
