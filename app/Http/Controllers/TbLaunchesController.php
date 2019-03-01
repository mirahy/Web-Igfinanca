<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\TbLaunchCreateRequest;
use App\Http\Requests\TbLaunchUpdateRequest;
use App\Repositories\TbLaunchRepository;
use App\Validators\TbLaunchValidator;

/**
 * Class TbLaunchesController.
 *
 * @package namespace App\Http\Controllers;
 */
class TbLaunchesController extends Controller
{
    /**
     * @var TbLaunchRepository
     */
    protected $repository;

    /**
     * @var TbLaunchValidator
     */
    protected $validator;

    /**
     * TbLaunchesController constructor.
     *
     * @param TbLaunchRepository $repository
     * @param TbLaunchValidator $validator
     */
    public function __construct(TbLaunchRepository $repository, TbLaunchValidator $validator)
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
        $tbLaunches = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbLaunches,
            ]);
        }

        return view('tbLaunches.index', compact('tbLaunches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TbLaunchCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
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
