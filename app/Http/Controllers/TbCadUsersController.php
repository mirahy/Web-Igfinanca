<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Entities\TbCadUser;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\TbCadUserCreateRequest;
use App\Http\Requests\TbCadUserUpdateRequest;
use App\Repositories\TbCadUserRepository;
use App\Validators\TbCadUserValidator;
use App\Services\TbCadUserService;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use App\Repositories\TbBaseRepository;
use App\Repositories\TbProfileRepository;


class TbCadUsersController extends Controller
{
    protected $TbProfileRepository;
    protected $TbBaseRepository;
    protected $repository;
    protected $service;

    public function __construct(TbCadUserRepository $repository, TbCadUserService $service, TbBaseRepository $TbBaseRepository, TbProfileRepository $TbProfileRepository)
    {
        $this->TbProfileRepository  = $TbProfileRepository;
        $this->TbBaseRepository     = $TbBaseRepository;
        $this->repository           = $repository;
        $this->service              = $service;

    }

    //redireciona para a view edit-user
    public function index()
    {
        return redirect('/edit-users');
    }

    //redireciona para a view register
    public function register()
    {
        return view('user.register');
    }

    //redireciona para a view forgot-password
    public function forgotPassword()
    {
        return view('user.forgot-password');
    }
    

    //retorna dados para as tabelas do framework datatables
    Public function query_DataTables(Request $request)
    {

        if(request()->ajax()){
           
            return $this->service->find_DataTables($request);
        }

        
        $perfil_list  = $this->TbProfileRepository->selectBoxList();
        $base_list    = $this->TbBaseRepository->selectBoxList();

        return view('user.edit-users',[
            'perfil_list'  => $perfil_list,
            'base_list'    => $base_list,
        ]);

    }

    
    //função para cadastar e atualizar
    public function keep(Request $request)
    {

        $json  = array();
        $json["status"] = 1;
        $json["error_list"] = array();
        $json["success"] = array();
        

        if(!$request["id"]){

            
            
            $request = $this->service->store($request->all()); 
            $user = $request['success'] ? $request['data'] : null;

            
            session()->flash('success', [
                'success'   =>  $request['success'],
                'messages'  =>  $request['messages'],
                'usuario'   =>  $user,
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
            $user = $request['success'] ? $request['data'] : null;

            session()->flash('success', [
                'success'   =>  $request['success'],
                'messages'  =>  $request['messages'],
                'usuario'   =>  $user,
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

    
    //função para retornar usuário pelo id
    public function show_user(Request $request)
    {
        $json  = array();
        $json["status"] = 1;
        $json["imput"] = array();


        $request = $this->service->find_Id($request["id"]); 
        $user = $request['success'] ? $request['data'] : null;
        $json["imput"]['id'] = $user['id'];
        $json["imput"]['name'] = $user['name'];
        $json["imput"]['email'] = $user['email'];
        $json["imput"]['idtb_profile'] = $user['idtb_profile'];
        $json["imput"]['idtb_base'] = $user['idtb_base'];
        $json["imput"]['status'] = $user['status'];

        echo json_encode($json);

    }

    //função para deletar usuário pelo id
    public function destroy(Request $request)
    {
        $json  = array();
        $json["status"] = 1;
        
        $request = $this->service->delete($request["id"]); 
        $user = $request['success'] ? $request['data'] : null;

        session()->flash('success', [
            'success'   =>  $request['success'],
            'messages'  =>  $request['messages'],
            'usuario'   =>  $user,
        ]);
           
        if(!$request['success']){
            $i=0;
            $json["status"] = 0;
            foreach($request['messages'] as $msg){
            $json["error_list"]["#".$request['type'][$i]."_edit"] = $msg;
            $i++;
            } 
        }
            
        echo json_encode($json);
    }


    //retorna nomes dos usuários apartir do textos digitados nos formulário
    public function autocomplete(Request $request)
    {

        $json  = array();   

        $request = $this->service->find_Autocomplete($request['term']);

        if($request){
            $i=0;
            foreach($request as $msg){
            $json[$i] = $msg['name'];

            $i++;
            } 
        }

        return $json;
    }
}
