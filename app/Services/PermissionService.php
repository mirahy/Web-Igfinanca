<?php

namespace App\Services;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Entities\Permissions;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Services\ReplicaDbService;
use App\Http\Controllers\ConnectDbController;
use Illuminate\Support\Facades\Validator;
use DB;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB as FacadesDB;
use PhpParser\Node\Stmt\TryCatch;
use Prettus\Validator\Exceptions\ValidatorException;

class PermissionService
{
    private $ReplicaDbService;
    private $ConnectDbController;

    public function __construct( ReplicaDbService $ReplicaDbService, ConnectDbController $ConnectDbController)
    {
        $this->ReplicaDbService     = $ReplicaDbService;
        $this->ConnectDbController  = $ConnectDbController;
    }

    // cadastar função
    public function store($data)
    {
        try {

            // validando campos
            $valida = $data->all();
            $messages = [
                'name.required'            => 'Nome deve ser informado!',
                'name.unique'              => 'Permissão já registrada!',
            ];
            $validator = Validator::make($data->all(), [
                'name'         => 'bail|required|unique:permissions,name',
            ], $messages);
            if ($validator->fails()) {
                return ['success' => false, 'messages' => $validator->getMessageBag()->all(), 'type'  => $validator->getMessageBag()->keys()];
            }


            // registra no banco de dados matriz
            $permission = Permissions::create(['name' => $data->input('name')]);

            // registra no banco de dados das filiais
            $this->ReplicaDbService->createPermission($data);

            //altera a conexão para base matriz
            $this->ConnectDbController->connectBase();

            $name = $permission['name'];

            return [
                'success'     => true,
                'messages'    => [$name . " adicionada"],
                'data'        => $permission,
                'type'        => ["id"],
            ];
        } catch (Exception $e) {

            switch (get_class($e)) {
                case QueryException::class:
                    return ['success' => false, 'messages' => $e->getMessage(), 'type'  => ["id"]];
                case ValidatorException::class:
                    return ['success' => false, 'messages' => $e->getMessage(), 'type'  => $e->getMessage()];
                case Exception::class:
                    return ['success' => false, 'messages' => $e->getMessage(), 'type'  => ["id"]];
                default:
                    return ['success' => false, 'messages' => $e->getMessage(), 'type'  => ["id"]];
            }
        }
    }

    //atualizar função
    public function update($data)
    {
        try {

            $id = $data['id'];

            // validando campos
            $valida = $data->all();
            $messages = [
                'name.required'            => 'Nome deve ser informado!',
                'name.unique'              => 'Permissão já registrada!',
            ];
            $validator = Validator::make($data->all(), [
                'name'         => 'bail|required|unique:permissions,name',
            ], $messages);
            if ($validator->fails()) {
                return ['success' => false, 'messages' => $validator->getMessageBag()->all(), 'type'  => $validator->getMessageBag()->keys()];
            }

            // atualiza no banco de dados matriz
            $permission = Permissions::find($id);
            $permission->name = $data->input('name');
            $permission->save();

            // atualiza no banco de dados das filiais
            $this->ReplicaDbService->updatePermission($data, $id);

            //altera a conexão para base matriz
            $this->ConnectDbController->connectBase();

            $name = $permission->name;

            return [
                'success'     => true,
                'messages'    => [$name . " editada"],
                'data'        => $permission,
                'type'        => [""],
            ];
        } catch (Exception $e) {

            switch (get_class($e)) {
                case QueryException::class:
                    return ['success' => false, 'messages' => 'Não foi possivel cadastar o período!', 'type'  => $e->getMessage()];
                case ValidatorException::class:
                    return ['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
                case Exception::class:
                    return ['success' => false, 'messages' => 'Não foi possivel cadastar o período!', 'type'  => $e->getMessage()];
                default:
                    return ['success' => false, 'messages' => 'Não foi possivel cadastar o período!', 'type'  => $e->getMessage()];
            }
        }
    }

    //retorna função pelo id
    public function find_Id($id)
    {

        try {

            $permission = Permissions::find($id);
    
            return [
                'success'     => true,
                'messages'    => null,
                'data'        => [$permission->toArray()] ,
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



    //retorna Funções para uso na tabelas do framework datatables
    public function find_DataTables($request)
    {

        $def = '%';

        try {
            return  Datatables::of(Permissions::query()
            ->where([
                ['id', 'LIKE', $request->query('id', $def)],
                ['name', 'LIKE', $request->query('name', $def)],
            ])
            ->orderByRaw('id'))
            ->blacklist(['action'])
            ->make(true);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return $th->getMessage();
        }

        
    }

    //função deletar lançamento
  public function delete($id)
  {

    try {

      // deleta no banco de dados matriz
      FacadesDB::table("permissions")->where('id',$id)->delete();

      // deleta no banco de dados das filiais
      $this->ReplicaDbService->deletePermission($id);

      //altera a conexão para base matriz
      $this->ConnectDbController->connectBase();

      return [
        'success'     => true,
        'messages'    => "",
        'data'        => "",
        'type'        => [""],
      ];
    } catch (Exception $e) {

      switch (get_class($e)) {
        case QueryException::class:
          return ['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
        case ValidatorException::class:
          return ['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
        case \Exception::class:
          return ['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
        default:
          return ['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
      }
    }
  }
}
