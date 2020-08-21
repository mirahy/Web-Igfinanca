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

    //redireciona para a view launchs_e e retorna dados para o form da view
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

    //redireciona para a view launchs_s e retorna dados para o form da view
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

    //redireciona para a view launchs_apr e retorna dados para o form da view
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

    //redireciona para a view closings e retorna dados para os imputs da view
    public function index_reports()
    {
        
        return view('reports.closings',[
                    'data'      => CONSTANT_MES,
                    'year'      => date("Y"),
                    'entries'   => 'Calculando...',
                    'exits'     => 'Calculando...',
                    'balance'   => 'Calculando...',
        ]);
    }

    //retorna dados para as tabelas do framework datatables
    Public function query_DataTables(Request $request){
        
        if(request()->ajax()){

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

        $user = $this->service->find_User_name($request['name'])->toArray();

        if($user){
            $request['id_user'] = $user[0]['id'];

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

    //função para retornar laçamentos pelo id
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

    //função para deletar laçamentos pelo id
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

    //função para aprovar/reprovar laçamentos pelo id
    public function aprov_id(Request $request)
    {

        $request = $this->service->aprov_id($request->all()); 

        $json["success"] = $request['messages'];
        $json["status"] = $request['success'];
              
        echo json_encode($json);

    }

  
}
