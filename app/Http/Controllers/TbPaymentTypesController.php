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

/**
 * Class TbPaymentTypesController.
 *
 * @package namespace App\Http\Controllers;
 */
class TbPaymentTypesController extends Controller
{
    /**
     * @var TbPaymentTypeRepository
     */
    protected $repository;

    /**
     * @var TbPaymentTypeValidator
     */
    protected $validator;

    /**
     * TbPaymentTypesController constructor.
     *
     * @param TbPaymentTypeRepository $repository
     * @param TbPaymentTypeValidator $validator
     */
    public function __construct(TbPaymentTypeRepository $repository, TbPaymentTypeValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  TbPaymentTypeCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tbPaymentType = $this->repository->find($id);

        return view('tbPaymentTypes.edit', compact('tbPaymentType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TbPaymentTypeUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
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
                'message' => 'TbPaymentType deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'TbPaymentType deleted.');
    }
}
