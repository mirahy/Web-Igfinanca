<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\TbProfileCreateRequest;
use App\Http\Requests\TbProfileUpdateRequest;
use App\Repositories\TbProfileRepository;
use App\Validators\TbProfileValidator;

/**
 * Class TbProfilesController.
 *
 * @package namespace App\Http\Controllers;
 */
class TbProfilesController extends Controller
{
    /**
     * @var TbProfileRepository
     */
    protected $repository;

    /**
     * @var TbProfileValidator
     */
    protected $validator;

    /**
     * TbProfilesController constructor.
     *
     * @param TbProfileRepository $repository
     * @param TbProfileValidator $validator
     */
    public function __construct(TbProfileRepository $repository, TbProfileValidator $validator)
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
        $tbProfiles = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbProfiles,
            ]);
        }

        return view('tbProfiles.index', compact('tbProfiles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TbProfileCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(TbProfileCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $tbProfile = $this->repository->create($request->all());

            $response = [
                'message' => 'TbProfile created.',
                'data'    => $tbProfile->toArray(),
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
        $tbProfile = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbProfile,
            ]);
        }

        return view('tbProfiles.show', compact('tbProfile'));
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
        $tbProfile = $this->repository->find($id);

        return view('tbProfiles.edit', compact('tbProfile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TbProfileUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(TbProfileUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $tbProfile = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'TbProfile updated.',
                'data'    => $tbProfile->toArray(),
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
                'message' => 'TbProfile deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'TbProfile deleted.');
    }
}
