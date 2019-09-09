<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\TbTypeLaunchCreateRequest;
use App\Http\Requests\TbTypeLaunchUpdateRequest;
use App\Repositories\TbTypeLaunchRepository;
use App\Validators\TbTypeLaunchValidator;

/**
 * Class TbTypeLaunchesController.
 *
 * @package namespace App\Http\Controllers;
 */
class TbTypeLaunchesController extends Controller
{
    /**
     * @var TbTypeLaunchRepository
     */
    protected $repository;

    /**
     * @var TbTypeLaunchValidator
     */
    protected $validator;

    /**
     * TbTypeLaunchesController constructor.
     *
     * @param TbTypeLaunchRepository $repository
     * @param TbTypeLaunchValidator $validator
     */
    public function __construct(TbTypeLaunchRepository $repository, TbTypeLaunchValidator $validator)
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
        $tbTypeLaunches = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbTypeLaunches,
            ]);
        }

        return view('tbTypeLaunches.index', compact('tbTypeLaunches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TbTypeLaunchCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(TbTypeLaunchCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $tbTypeLaunch = $this->repository->create($request->all());

            $response = [
                'message' => 'TbTypeLaunch created.',
                'data'    => $tbTypeLaunch->toArray(),
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
        $tbTypeLaunch = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbTypeLaunch,
            ]);
        }

        return view('tbTypeLaunches.show', compact('tbTypeLaunch'));
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
        $tbTypeLaunch = $this->repository->find($id);

        return view('tbTypeLaunches.edit', compact('tbTypeLaunch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TbTypeLaunchUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(TbTypeLaunchUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $tbTypeLaunch = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'TbTypeLaunch updated.',
                'data'    => $tbTypeLaunch->toArray(),
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
                'message' => 'TbTypeLaunch deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'TbTypeLaunch deleted.');
    }
}
