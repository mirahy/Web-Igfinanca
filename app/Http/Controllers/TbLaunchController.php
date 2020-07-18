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
    
    protected $repository; 
    protected $service;
    

   
    public function __construct(TbLaunchRepository $repository, TbLaunchService $service)
    {
        $this->repository  = $repository;
        $this->service  = $service;
    }

    
    public function index()
    {
        
        return view('launch.launchs_e', [
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

    public function index_s()
    {
        
        return view('launch.launchs_s', [
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

    Public function query_dizimos(Request $request){
        
        if(request()->ajax()){
           
            return Datatables::of(TbLaunch::query()
                                    ->with('user')
                                    ->with('type_launch')
                                    ->where('idtb_type_launch', '1'))
                                    ->blacklist(['action'])
                                    ->make(true);
        }
           

    }

    Public function query_ofertas(Request $request){


        if(request()->ajax()){
            return Datatables::of(TbLaunch::query()
                                    ->with('user')
                                    ->with('type_launch')
                                    ->where('idtb_type_launch', '2'))
                                    ->blacklist(['action'])
                                    ->make(true);
        }

    }

    Public function query_buy(Request $request){


        if(request()->ajax()){
            return Datatables::of(TbLaunch::query()
                                    ->with('user')
                                    ->with('type_launch')
                                    ->where('idtb_type_launch', '3'))
                                    ->blacklist(['action'])
                                    ->make(true);
        }

    }

    Public function query_service(Request $request){


        if(request()->ajax()){
            return Datatables::of(TbLaunch::query()
                                    ->with('user')
                                    ->with('type_launch')
                                    ->where('idtb_type_launch', '4'))
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
                } else{
                    $json["success"] = $request['messages'];

                }
            

        }else{
            

            $request = $this->service->update($request->all()); 
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

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tbLaunch = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbLaunch,
            ]);
        }

        return view('tbLaunches.show', compact('tbLaunch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tbLaunch = $this->repository->find($id);

        return view('tbLaunches.edit', compact('tbLaunch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TbLaunchUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(TbLaunchUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $tbLaunch = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'TbLaunch updated.',
                'data'    => $tbLaunch->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     $deleted = $this->repository->delete($id);

    //     if (request()->wantsJson()) {

    //         return response()->json([
    //             'message' => 'TbLaunch deleted.',
    //             'deleted' => $deleted,
    //         ]);
    //     }

    //     return redirect()->back()->with('message', 'TbLaunch deleted.');
    // }
}
