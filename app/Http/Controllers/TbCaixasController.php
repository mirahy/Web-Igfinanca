<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\TbCaixaCreateRequest;
use App\Http\Requests\TbCaixaUpdateRequest;
use App\Repositories\TbCaixaRepository;
use App\Validators\TbCaixaValidator;

/**
 * Class TbCaixasController.
 *
 * @package namespace App\Http\Controllers;
 */
class TbCaixasController extends Controller
{
    /**
     * @var TbCaixaRepository
     */
    protected $repository;

    /**
     * @var TbCaixaValidator
     */
    protected $validator;

    /**
     * TbCaixasController constructor.
     *
     * @param TbCaixaRepository $repository
     * @param TbCaixaValidator $validator
     */
    public function __construct(TbCaixaRepository $repository, TbCaixaValidator $validator)
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
        $tbCaixas = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbCaixas,
            ]);
        }

        return view('tbCaixas.index', compact('tbCaixas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TbCaixaCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(TbCaixaCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $tbCaixa = $this->repository->create($request->all());

            $response = [
                'message' => 'TbCaixa created.',
                'data'    => $tbCaixa->toArray(),
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
        $tbCaixa = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $tbCaixa,
            ]);
        }

        return view('tbCaixas.show', compact('tbCaixa'));
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
        $tbCaixa = $this->repository->find($id);

        return view('tbCaixas.edit', compact('tbCaixa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TbCaixaUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(TbCaixaUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $tbCaixa = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'TbCaixa updated.',
                'data'    => $tbCaixa->toArray(),
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
                'message' => 'TbCaixa deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'TbCaixa deleted.');
    }
}
