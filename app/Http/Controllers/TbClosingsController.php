<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\TbClosingCreateRequest;
use App\Http\Requests\TbClosingUpdateRequest;
use App\Repositories\TbClosingRepository;
use App\Validators\TbClosingValidator;
use App\Services\TbClosingsService;


const MONTH = ['Mês', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

class TbClosingsController extends Controller
{
    
    protected $repository;
    protected $validator;
    protected $service;

   
    public function __construct(TbClosingRepository $repository, TbClosingValidator $validator, TbClosingsService $service)
    {

        $this->middleware('role:Admin|Edit|launche-approver', ['only' => ['index', 'query_DataTables', 'keep', 'show_closing', 'destroy']]);
        $this->repository       = $repository;
        $this->validator        = $validator;
        $this->service          = $service;
    }

    //retorna view closing
    public function index()
    {

        $year = [date("Y")-1 => date("Y")-1,
                 date("Y")+0 => date("Y")+0,
                 date("Y")+1 => date("Y")+1];

        return view('launch.closing',[
            'month'        => MONTH,
            'year'         => $year,
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
         
         $request['month'] = $request['month'] ? MONTH[$request['month']]: null;
         if(!$request["id"]){
           
             $request = $this->service->store($request->all()); 
             $closing = $request['success'] ? $request['data'] : null;


             session()->flash('success', [
                 'success'   =>  $request['success'],
                 'messages'  =>  $request['messages'],
                 'periodo'   =>  $closing,
              ]);

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
             $closing = $request['success'] ? $request['data'] : null;
 
             session()->flash('success', [
                 'success'   =>  $request['success'],
                 'messages'  =>  $request['messages'],
                 'periodo'   =>  $closing,
              ]);
              
              if(!$request['success']){
                 $i=0;
                 $json["status"] = 0;
                   foreach($request['messages'] as $msg){
                       $json["error_list"]["#".$request['type'][$i]] = $msg;
                       $i++;
                   } 
                 }      
             
         }      
          
              echo json_encode($json);
 
     }

    //função para retornar laçamentos pelo id
    public function show_closing(Request $request)
    {
        $json  = array();
        $json["status"] = 1;
        $json["imput"] = array();
        

        $request = $this->service->find_Id($request["id"]); 
        $closing = $request['success'] ? $request['data'] : null;
        
        $json["imput"]['id'] = $closing['id'];
        $json["imput"]['month'] = array_search( $closing['month'], MONTH );
        $json["imput"]['year'] = $closing['year'];
        $json["imput"]['status'] = $closing['status'];

        echo json_encode($json);

    }

    //função para deletar laçamentos pelo id
    public function destroy(Request $request)
    {
        $json  = array();
        $json["status"] = 1;
        
        $request = $this->service->delete($request["id"]); 
        $closing = $request['success'] ? $request['data'] : null;

        session()->flash('success', [
            'success'   =>  $request['success'],
            'messages'  =>  $request['messages'],
            'launch'   =>  $closing,
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
