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

    protected $repository;
    protected $validator;


    public function __construct(TbTypeLaunchRepository $repository, TbTypeLaunchValidator $validator)
    {
        $this->middleware('role:Admin', ['only' => ['index', 'store', 'show', 'edit', 'update', 'destroy']]);
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    
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

   
    public function edit($id)
    {
        $tbTypeLaunch = $this->repository->find($id);

        return view('tbTypeLaunches.edit', compact('tbTypeLaunch'));
    }

   
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
