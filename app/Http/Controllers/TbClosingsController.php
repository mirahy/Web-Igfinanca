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
        $this->repository       = $repository;
        $this->validator        = $validator;
        $this->service          = $service;
    }

    //retorna view closing
    public function index()
    {
        return view('launch.closing',[
            'month'        => MONTH,
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
         
         $request['month'] = MONTH[$request['month']];
         
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
                 'usuario'   =>  $closing,
              ]);
              
              if(!$request['success']){
                 $i=0;
                 $json["status"] = 0;
                   foreach($request['messages'] as $msg){
                       $json["error_list"]["#".$request['type'][$i]."_edit"] = $msg;
                       $i++;
                   } 
                 }      
             
         }      
          
              echo json_encode($json);
 
     }

   
  
}
