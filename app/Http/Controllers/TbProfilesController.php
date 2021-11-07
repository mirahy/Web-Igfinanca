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


class TbProfilesController extends Controller
{
    
    protected $repository;
    protected $validator;

   
    public function __construct(TbProfileRepository $repository, TbProfileValidator $validator)
    {
        $this->middleware('role:Admin', ['only' => ['index', 'store', 'show', 'edit', 'update', 'destroy']]);
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    
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

   
    public function edit($id)
    {
        $tbProfile = $this->repository->find($id);

        return view('tbProfiles.edit', compact('tbProfile'));
    }

    
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
