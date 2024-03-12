<?php

namespace App\Services;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Entities\Roles;
use Spatie\Permission\Models\Permission;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Services\ReplicaDbService;
use App\Http\Controllers\ConnectDbController;
use Illuminate\Support\Facades\Validator;
use DB;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB as FacadesDB;
use Prettus\Validator\Exceptions\ValidatorException;



class RoleService
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
                'name.unique'              => 'Função já registrada!',
                'permission.required'      => 'As permissões devem ser informadas!',
            ];
            $validator = Validator::make($data->all(), [
                'name'         => 'bail|required|unique:roles,name',
                'permission'   => 'bail|required',
            ], $messages);
            if ($validator->fails()) {
                return ['success' => false, 'messages' => $validator->getMessageBag()->all(), 'type'  => $validator->getMessageBag()->keys()];
            }


            // registra no banco de dados matriz
            $role = Roles::create(['name' => $data->input('name')]);
            $role->syncPermissions($data->input('permission'));

            // registra no banco de dados das filiais
            $this->ReplicaDbService->createRole($data);

            //altera a conexão para base matriz
            $this->ConnectDbController->connectBase();

            $name = $role['name'];

            return [
                'success'     => true,
                'messages'    => [$name . " adicionada"],
                'data'        => $role,
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

    //atualizar função
    public function update($data)
    {
        try {

            $id = $data['id'];

            // validando campos
            $valida = $data->all();
            $messages = [

                'name.required'            => 'Nome deve ser informado!',
                'name.unique'              => 'Função já registrada!',
                'permission.required'      => 'As permissões devem ser informadas!',
            ];
            $validator = Validator::make($data->all(), [
                'name'         => 'bail|required',
                'permission'   => 'bail|required',
            ], $messages);
            if ($validator->fails()) {
                return ['success' => false, 'messages' => $validator->getMessageBag()->all(), 'type'  => $validator->getMessageBag()->keys()];
            }

            // atualiza no banco de dados matriz
            $role = Roles::find($id);
            $role->name = $data->input('name');
            $role->save();

            //atualiza permissões
            $role->syncPermissions($data->input('permission'));

            // atualiza no banco de dados das filiais
            $this->ReplicaDbService->updateRole($data, $id);

            //altera a conexão para base matriz
            $this->ConnectDbController->connectBase();

            $name = $role->name;

            return [
                'success'     => true,
                'messages'    => [$name . " editada"],
                'data'        => $role,
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

            $role = Roles::find($id);
            $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
                ->where("role_has_permissions.role_id",$id)
                ->select('id', 'name')->get();


            return [
                'success'     => true,
                'messages'    => null,
                'data'        => [$role->toArray()] ,
                'data2'       => [$rolePermissions->toArray()],
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

        return  Datatables::of(Roles::query()
            ->where([
                ['id', 'LIKE', $request->query('id', $def)],
                ['name', 'LIKE', $request->query('name', $def)],
            ])
            ->orderByRaw('id'))
            ->blacklist(['action'])
            ->make(true);
    }

    //função deletar lançamento
  public function delete($id)
  {

    try {

      // deleta no banco de dados matriz
      FacadesDB::table("roles")->where('id',$id)->delete();

      // deleta no banco de dados das filiais
      $this->ReplicaDbService->deleteRole($id);

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
        case Exception::class:
          return ['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
        default:
          return ['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
      }
    }
  }
}
