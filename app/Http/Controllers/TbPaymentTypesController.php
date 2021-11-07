<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\TbPaymentTypeCreateRequest;
use App\Http\Requests\TbPaymentTypeUpdateRequest;
use App\Repositories\TbPaymentTypeRepository;
use App\Validators\TbPaymentTypeValidator;


class TbPaymentTypesController extends Controller
{
    protected $repository;
    protected $validator;

    public function __construct(TbPaymentTypeRepository $repository, TbPaymentTypeValidator $validator)
    {
        $this->middleware('role:Admin', ['only' => ['index', 'store', 'show', 'edit', 'update', 'destroy']]);
        $this->repository = $repository;
        $this->validator  = $validator;
    }


    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $tbPaymentTypes = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbPaymentTypes,
            ]);
        }

        return view('tbPaymentTypes.index', compact('tbPaymentTypes'));
    }

    
    public function store(TbPaymentTypeCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $tbPaymentType = $this->repository->create($request->all());

            $response = [
                'message' => 'TbPaymentType created.',
                'data'    => $tbPaymentType->toArray(),
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
        $tbPaymentType = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbPaymentType,
            ]);
        }

        return view('tbPaymentTypes.show', compact('tbPaymentType'));
    }

   
    public function edit($id)
    {
        $tbPaymentType = $this->repository->find($id);

        return view('tbPaymentTypes.edit', compact('tbPaymentType'));
    }


    public function update(TbPaymentTypeUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $tbPaymentType = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'TbPaymentType updated.',
                'data'    => $tbPaymentType->toArray(),
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
                'message' => 'TbPaymentType deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'TbPaymentType deleted.');
    }
}
