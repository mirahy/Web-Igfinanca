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

/**
 * Class TbBasesController.
 *
 * @package namespace App\Http\Controllers;
 */
class TbBasesController extends Controller
{
    /**
     * @var TbBaseRepository
     */
    protected $repository;

    /**
     * @var TbBaseValidator
     */
    protected $validator;

    /**
     * TbBasesController constructor.
     *
     * @param TbBaseRepository $repository
     * @param TbBaseValidator $validator
     */
    public function __construct(TbBaseRepository $repository, TbBaseValidator $validator)
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
        $tbBases = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbBases,
            ]);
        }

        return view('tbBases.index', compact('tbBases'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TbBaseCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tbBase = $this->repository->find($id);

        return view('tbBases.edit', compact('tbBase'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TbBaseUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
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
                'message' => 'TbBase deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'TbBase deleted.');
    }
}
