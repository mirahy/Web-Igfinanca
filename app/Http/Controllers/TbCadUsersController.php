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

/**
 * Class TbCadUsersController.
 *
 * @package namespace App\Http\Controllers;
 */
class TbCadUsersController extends Controller
{

    protected $repository;
    protected $service;

    public function __construct(TbCadUserRepository $repository, TbCadUserService $service)
    {

        $this->repository    = $repository;
        $this->service       = $service;

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
            return Datatables::of(TbCadUser::query()->where('status', '1'))->blacklist(['action'])->make(true);
        }
        return view('user.edit-users');


    }

    Public function query_inact(Request $request){

        if(request()->ajax()){
            return Datatables::of(TbCadUser::query()->where('status', '0'))->blacklist(['action'])->make(true);
        }
        return view('user.edit-users');


    }

    

    public function store(TbCadUserCreateRequest $request)
    {
        
         $request = $this->service->store($request->all()); 
         $usuario = $request['success'] ? $request['data'] : null;

         session()->flash('success', [
            'success'   =>  $request['success'],
            'messages'  =>  $request['messages'],
            'usuario'   =>  $usuario,
         ]);

         return view('user.register');

                            /*try {

                                $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

                                $tbCadUser = $this->repository->create($request->all());

                                $response = [
                                    'message' => 'TbCadUser created.',
                                    'data'    => $tbCadUser->toArray(),
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
                            }*/
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
        $tbCadUser = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbCadUser,
            ]);
        }

        return view('tbCadUsers.show', compact('tbCadUser'));
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
        $tbCadUser = $this->repository->find($id);

        return view('tbCadUsers.edit', compact('tbCadUser'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TbCadUserUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(TbCadUserUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $tbCadUser = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'TbCadUser updated.',
                'data'    => $tbCadUser->toArray(),
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
                'message' => 'TbCadUser deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'TbCadUser deleted.');
    }
}
