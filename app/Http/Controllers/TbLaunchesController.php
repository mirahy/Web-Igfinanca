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


const CONSTANT_MES = ['JANEIRO', 'FEVEREIRO', 'MARÇO', 'ABRIL','MAIO','JUNHO','JULHO','AGOSTO','SETEMBRO','OUTUBRO','NOVEMBRO','DEZENBRO'];

/**
 * Class TbLaunchesController.
 *
 * @package namespace App\Http\Controllers;
 */
class TbLaunchesController extends Controller
{
    
    protected $repository; 
    protected $validator;
    

   
    public function __construct(TbLaunchRepository $repository, TbLaunchValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    
    public function index()
    {
        

        return view('launch.launchs', [
            'year'         => date("Y"),
            'operation'    => 0,
            'type_launch'  => 0,
            'base'         => 0,
            'closing'      => 0,
            'data'         => CONSTANT_MES,
        ]);
    }

    Public function query_dizimos(Request $request){

        // dd(Datatables::of(TbLaunch::query()
        // ->with('user')
        // ->with('launch')
        // ->where('idtb_type_launch', '1'))
        // ->blacklist(['action'])
        // ->make(true));
        
        if(request()->ajax()){
           
            return Datatables::of(TbLaunch::query()
                                    ->with('user')
                                    ->with('launch')
                                    ->where('idtb_type_launch', '1'))
                                    ->blacklist(['action'])
                                    ->make(true);
        }
           

    }

    Public function query_ofertas(Request $request){


        if(request()->ajax()){
            return Datatables::of(TbLaunch::query()
                                    ->with('user')
                                    ->with('launch')
                                    ->where('idtb_type_launch', '2'))
                                    ->blacklist(['action'])
                                    ->make(true);
        }

    }


    public function store(TbLaunchCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $tbLaunch = $this->repository->create($request->all());

            $response = [
                'message' => 'TbLaunch created.',
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
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'TbLaunch deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'TbLaunch deleted.');
    }
}
