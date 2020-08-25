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

    
    public function store(TbClosingCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $tbClosing = $this->repository->create($request->all());

            $response = [
                'message' => 'TbClosing created.',
                'data'    => $tbClosing->toArray(),
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

   
    public function show($id)
    {
        $tbClosing = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbClosing,
            ]);
        }

        return view('tbClosings.show', compact('tbClosing'));
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
        $tbClosing = $this->repository->find($id);

        return view('tbClosings.edit', compact('tbClosing'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TbClosingUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(TbClosingUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $tbClosing = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'TbClosing updated.',
                'data'    => $tbClosing->toArray(),
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
                'message' => 'TbClosing deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'TbClosing deleted.');
    }
}
