<?php

namespace App\Services;

use App\Http\Controllers\ConnectDbController;
use App\Entities\TbBase;
use App\Entities\TbCadUser;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Traits\LogsActivity;
use DB;


class ReplicaDbService
{
    use LogsActivity;

    private $ConnectDbController;
    private $bases;

    public function __construct(
        ConnectDbController $ConnectDbController)
    {

        $this->ConnectDbController    = $ConnectDbController;
        //recupera o nome das bases
        $this->bases = TbBase::query()->select('sigla')->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Replicadores genéricos
    |--------------------------------------------------------------------------
    */


    public function create($data, $repository)
    {
        //destivando registro de log para as replicações
        activity()->disableLogging();
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // registra no banco de dados das filiais
                $repository->create($data);
            }
        }
         //ativando registro de log
         activity()->enableLogging();
    }

    public function update($data, $id, $repository)
    {
        //destivando registro de log para as replicações
        activity()->disableLogging();
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // atualiza no banco de dados das filiais
                $repository->update($data, $id);
            }
        }
         //ativando registro de log
         activity()->enableLogging();
    }

    public function delete($id, $repository)
    {
        //destivando registro de log para as replicações
        activity()->disableLogging();
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // deleta no banco de dados das filiais
                $repository->delete($id);
            }
        }
         //ativando registro de log
         activity()->enableLogging();
    }


    /*
    |--------------------------------------------------------------------------
    | Replicadores para a classe roles
    |--------------------------------------------------------------------------
    */


    public function createRole($data)
    {
        //destivando registro de log para as replicações
        activity()->disableLogging();
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // registra no banco de dados das filiais
                $role = Role::create(['name' => $data->input('name')]);
                $role->syncPermissions($data->input('permission'));
            }
        }
         //ativando registro de log
         activity()->enableLogging();
    }

    public function updateRole($data, $id)
    {
        //destivando registro de log para as replicações
        activity()->disableLogging();
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // atualiza no banco de dados das filiais
                $role = Role::find($id);
                $role->name = $data->input('name');
                $role->save();

                //atualiza permissões
                $role->syncPermissions($data->input('permission'));
            }
        }
         //ativando registro de log
         activity()->enableLogging();
    }

    public function deleteRole($id)
    {
        //destivando registro de log para as replicações
        activity()->disableLogging();
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // deleta no banco de dados das filiais
                DB::table("roles")->where('id',$id)->delete();
            }
        }
        //ativando registro de log
        activity()->enableLogging();
    }


     /*
    |--------------------------------------------------------------------------
    | Replicadores para a classe de usuários
    |--------------------------------------------------------------------------
    */

    public function createUser($data, $repository)
    {
        //destivando registro de log para as replicações
        activity()->disableLogging();
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // registra no banco de dados das filiais
                $user = $repository->create($data->all());
                $user->assignRole($data->input('roles'));
            }
        }
        //ativando registro de log
        activity()->enableLogging();
    }

    public function updateUser($data, $id, $repository)
    {
        //destivando registro de log para as replicações
        activity()->disableLogging();
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // atualiza no banco de dados das filiais
                $user = $repository->update($data->all(), $id);
                DB::table('model_has_roles')->where('model_id',$id)->delete();
                $user->assignRole($data->input('roles'));
            }
        }
        //ativando registro de log
        activity()->enableLogging();
    }


     /*
    |--------------------------------------------------------------------------
    | Replicadores para a classe roles
    |--------------------------------------------------------------------------
    */


    public function createPermission($data)
    {
        //destivando registro de log para as replicações
        activity()->disableLogging();
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // registra no banco de dados das filiais
                $role = Permission::create(['name' => $data->input('name')]);
            }
        }
         //ativando registro de log
         activity()->enableLogging();
    }

    public function updatePermission($data, $id)
    {
        //destivando registro de log para as replicações
        activity()->disableLogging();
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // atualiza no banco de dados das filiais
                $permission = Permission::find($id);
                $permission->name = $data->input('name');
                $permission->save();

            }
        }
         //ativando registro de log
         activity()->enableLogging();
    }

    public function deletePermission($id)
    {
        //destivando registro de log para as replicações
        activity()->disableLogging();
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // deleta no banco de dados das filiais
                DB::table("permissions")->where('id',$id)->delete();
            }
        }
         //ativando registro de log
         activity()->enableLogging();
    }

}

