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

/**
 * Class TbCadUsersController.
 *
 * @package namespace App\Http\Controllers;
 */
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


    public function index()
    {
        return view('user.index');
    }

    public function register()
    {
        return view('user.register');
    }

    public function forgotPassword()
    {
        return view('user.forgot-password');
    }
    


    Public function query(Request $request){

        if(request()->ajax()){
           
            return Datatables::of(TbCadUser::query()
                                    ->with('base')
                                    ->with('Profile')
                                    ->where('status', '1'))
                                    ->blacklist(['action'])
                                    ->make(true);
        }
        
        $perfil_list  = $this->TbProfileRepository->selectBoxList();
        $base_list    = $this->TbBaseRepository->selectBoxList();
        
        return view('user.edit-users',[
            'perfil_list'  => $perfil_list,
            'base_list'    => $base_list,
        ]);

    }

    Public function query_inact(Request $request){

        if(request()->ajax()){
            return Datatables::of(TbCadUser::query()
                                    ->with('base')
                                    ->with('Profile')
                                    ->where('status', '0'))
                                    ->blacklist(['action'])
                                    ->make(true);
        }

        
        return view('user.edit-users');

    }

    

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

    public function select(){


        $perfil_list = \App\Entities\TbProfile::pluck('name', 'idtb_profile')->All();
        $base_list = \App\Entities\TbBase::pluck('name', 'idtb_base')->All();
        //dd($perfil_list);
        return ([
            $perfil_list,
            $base_list,
        ]);

        //dd(json_encode($json));

        //echo json_encode($json);
    }

    public function autocomplete(Request $request){

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
