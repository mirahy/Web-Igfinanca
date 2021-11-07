<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\TbTypeLauncCreateRequest;
use App\Http\Requests\TbTypeLauncUpdateRequest;
use App\Repositories\TbTypeLauncRepository;
use App\Validators\TbTypeLauncValidator;


class TbTypeLauncsController extends Controller
{
   
    protected $repository;
    protected $validator;

    public function __construct(TbTypeLauncRepository $repository, TbTypeLauncValidator $validator)
    {
        $this->middleware('role:Admin', ['only' => ['index', 'store', 'show', 'edit', 'update', 'destroy']]);
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $tbTypeLauncs = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbTypeLauncs,
            ]);
        }

        return view('tbTypeLauncs.index', compact('tbTypeLauncs'));
    }

    
    public function store(TbTypeLauncCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $tbTypeLaunc = $this->repository->create($request->all());

            $response = [
                'message' => 'TbTypeLaunc created.',
                'data'    => $tbTypeLaunc->toArray(),
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
        $tbTypeLaunc = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbTypeLaunc,
            ]);
        }

        return view('tbTypeLauncs.show', compact('tbTypeLaunc'));
    }

    
    public function edit($id)
    {
        $tbTypeLaunc = $this->repository->find($id);

        return view('tbTypeLauncs.edit', compact('tbTypeLaunc'));
    }

    
    public function update(TbTypeLauncUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $tbTypeLaunc = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'TbTypeLaunc updated.',
                'data'    => $tbTypeLaunc->toArray(),
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
                'message' => 'TbTypeLaunc deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'TbTypeLaunc deleted.');
    }
}
