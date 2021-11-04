<?php

namespace App\Services;

use App\Http\Controllers\ConnectDbController;
use App\Entities\TbBase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;


class ReplicaDbService
{
    private $ConnectDbController;
    private $bases;

    public function __construct(ConnectDbController $ConnectDbController)
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

        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // registra no banco de dados das filiais
                $repository->create($data);
            }
        }
    }

    public function update($data, $id, $repository)
    {
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // atualiza no banco de dados das filiais
                $repository->update($data, $id);
            }
        }
    }

    public function delete($id, $repository)
    {
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // deleta no banco de dados das filiais
                $repository->delete($id);
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Replicadores para a classe roles
    |--------------------------------------------------------------------------
    */


    public function createRole($data)
    {

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
    }

    public function updateRole($data, $id)
    {
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
    }

    public function deleteRole($id)
    {
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // deleta no banco de dados das filiais
                DB::table("roles")->where('id',$id)->delete();
            }
        }
    }


     /*
    |--------------------------------------------------------------------------
    | Replicadores para a classe de usuários
    |--------------------------------------------------------------------------
    */

    public function createUser($data, $repository)
    {

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
    }

    public function updateUser($data, $id, $repository)
    {
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
    }


     /*
    |--------------------------------------------------------------------------
    | Replicadores para a classe roles
    |--------------------------------------------------------------------------
    */


    public function createPermission($data)
    {

        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // registra no banco de dados das filiais
                $role = Permission::create(['name' => $data->input('name')]);
            }
        }
    }

    public function updatePermission($data, $id)
    {
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
    }

    public function deletePermission($id)
    {
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // deleta no banco de dados das filiais
                DB::table("permissions")->where('id',$id)->delete();
            }
        }
    }

}

