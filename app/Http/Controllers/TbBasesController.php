<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\TbBaseCreateRequest;
use App\Http\Requests\TbBaseUpdateRequest;
use App\Repositories\TbBaseRepository;
use App\Validators\TbBaseValidator;


class TbBasesController extends Controller
{
   
    protected $validator;

    public function __construct(TbBaseRepository $repository, TbBaseValidator $validator)
    {
        $this->middleware('role:Admin', ['only' => ['index', 'store', 'show', 'edit', 'update', 'destroy']]);
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    
    public function index()
    {
        

        return view('launch.launchs', compact('tbBases'));
    }


    public function store(TbBaseCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $tbBase = $this->repository->create($request->all());

            $response = [
                'message' => 'TbBase created.',
                'data'    => $tbBase->toArray(),
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
        $tbBase = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbBase,
            ]);
        }

        return view('tbBases.show', compact('tbBase'));
    }

    public function edit($id)
    {
        $tbBase = $this->repository->find($id);

        return view('tbBases.edit', compact('tbBase'));
    }

    
    public function update(TbBaseUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $tbBase = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'TbBase updated.',
                'data'    => $tbBase->toArray(),
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


    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'TbBase deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'TbBase deleted.');
    }
}
