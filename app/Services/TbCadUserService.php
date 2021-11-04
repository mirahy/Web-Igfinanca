<?php

namespace App\Services;

use Exception;
use App\Entities\TbCadUser;
use App\Entities\TbBase;
use App\Validators\TbCadUserValidator;
use App\Repositories\TbCadUserRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Database\QueryException;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\ConnectDbController;
use DB;
use App\Services\ReplicaDbService;


class TbCadUserService
{

  private $repository;
  private $validator;
  private $ConnectDbController;
  private $ReplicaDbService;

  public function __construct(
    TbCadUserRepository $repository,
    TbCadUserValidator $validator,
    ConnectDbController $ConnectDbController,
    ReplicaDbService $ReplicaDbService
  ) {
    $this->repository             = $repository;
    $this->validator              = $validator;
    $this->ConnectDbController    = $ConnectDbController;
    $this->ReplicaDbService       = $ReplicaDbService;
  }

  //função cadastar usuário
  public function store($data)
  {
    try {

      if ($data['password'] == null && $data['password_confirmation']  == null) {

        $data['password'] = env('PATTERN_PASS');
        $data['password_confirmation'] = env('PATTERN_PASS');
      }

      // validando campos
      $this->validator->with($data->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

      // validando campos senhas
      $v = $this->validator->validaInputsPass($data->all());
      if (!$v['success']) {
        return $v;
      }

      //hash password?
      $data['password'] = env("PASSWORD_HASH") ? bcrypt($data['password']) : $data['password'];

      // registra no banco de dados matriz
      $user = $this->repository->create($data->all());
      $user->assignRole($data->input('roles'));

      // registra no banco de dados das filiais
      $this->ReplicaDbService->createUser($data, $this->repository);

      //altera a conexão para base matriz
      $this->ConnectDbController->connectBase();


      $name = explode(" ", $user['name']);

      return [
        'success'     => true,
        'messages'    => [$name[0]],
        'data'        => $user,
        'type'        => ["id"],
      ];
    } catch (Exception $e) {
   
      switch (get_class($e)) {
        case QueryException::class:
          return ['success' => false, 'messages' => $e->getMessage(), 'type'  => ["id"]];
        case ValidatorException::class:
          return ['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
        case Exception::class:
          return ['success' => false, 'messages' => $e->getMessage()->all(), 'type'  => ["id"]];
        default:
          return ['success' => false, 'messages' => $e->getMessage()->all(), 'type'  => ["id"]];
      }
    }
  }

  //função atualizar usuário
  public function update($data)
  {
    try {

      $id = $data['id'];

      $this->validator->with($data->all())->setId($id)->passesOrFail(ValidatorInterface::RULE_UPDATE);

      // registra no banco de dados matriz
      $user = $this->repository->update($data->all(), $id);
      DB::table('model_has_roles')->where('model_id',$id)->delete();
      $user->assignRole($data->input('roles'));

      // registra no banco de dados das filiais
      $this->ReplicaDbService->updateUser($data, $id, $this->repository);
      
      //altera a conexão para base matriz
      $this->ConnectDbController->connectBase();

      $name = explode(" ", $user['name']);

      return [
        'success'     => true,
        'messages'    => [$name[0]],
        'data'        => $user,
        'type'        => [""],
      ];
    } catch (Exception $e) {
      dd($e);
      switch (get_class($e)) {
        case QueryException::class:
          return ['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
        case ValidatorException::class:
          return ['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
        case Exception::class:
          return ['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
        default:
          return ['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
      }
    }
  }

  //função deletar usuário
  public function delete($id)
  {

    try {

      $user = TbCadUser::query()->where('id', $id)->select('name')->get();
      $name = explode(" ", $user[0]['name']);

      $this->repository->delete($id);

      // registra no banco de dados das filiais
      $this->ReplicaDbService->delete($id, $this->repository);

      //altera a conexão para base matriz
      $this->ConnectDbController->connectBase();

      return [
        'success'     => true,
        'messages'    => [$name[0]],
        'data'        => $user,
        'type'        => [""],
      ];
    } catch (Exception $e) {

      switch (get_class($e)) {
        case QueryException::class:
          return ['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
        case ValidatorException::class:
          return ['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
        case Exception::class:
          return ['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
        default:
          return ['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
      }
    }
  }

  //retorna usuario pelo id
  public function find_Id($id)
  {

    try {


      $user = TbCadUser::query()
        ->with('base')
        ->where('id', 'LIKE', $id)
        ->get();

      $userId = TbCadUser::find($id);
      $userRole = $userId->roles->pluck('name','id')->all();
      
      return [
        'success'     => true,
        'messages'    => null,
        'data'        => $user,
        'data2'       => $userRole,
        'type'        => null,
      ];
    } catch (Exception $e) {

      switch (get_class($e)) {
        case QueryException::class:
          return ['success' => false, 'messages' => $e->getMessage(), 'type'  => null];
        case ValidatorException::class:
          return ['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => null];
        case Exception::class:
          return ['success' => false, 'messages' => $e->getMessage(), 'type'  => null];
        default:
          return ['success' => false, 'messages' => $e->getMessage(), 'type'  => null];
      }
    }
  }

  //retorna nomes dos usuários apartir do textos digitados nos formulário
  public function find_Autocomplete($term)
  {

    $data =  TbCadUser::where([['name', 'LIKE', '%' . $term . '%'], ['status', '=', '1'], ['deleted_at', '=', null]])->get();

    return  $data;
  }
  //retorna usuários para uso na tabelas do framework datatables
  public function find_DataTables($request)
  {

    $def = '%';

    return Datatables::of(TbCadUser::query()
      ->with('base')
      ->with('Profile')
      ->where([['status', 'LIKE', $request->query('status', $def)]]))
      ->blacklist(['action'])
      ->make(true);
  }

  //retorna usuário pelo nome
  public function find_User_name($name)
  {

    $data =  TbCadUser::where('name', 'LIKE',  $name)->get();

    return  $data;
  }
}
