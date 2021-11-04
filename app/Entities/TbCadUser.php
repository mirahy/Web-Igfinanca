<?php

namespace App\Entities;

use Hash;
use App\Entities\Access;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Class TbCadUser.
 *
 * @package namespace App\Entities;
 */
class TbCadUser extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public     $timestamps   = true;
    protected  $table        = 'tb_cad_user';
    protected  $fillable     = ['id', 'name', 'idtb_profile', 'idtb_base', 'birth', 'email', 'password', 'status', 'permission', 'token_access', 'created_at', 'updated_at'];
    protected  $hidden       = ['password', 'rememberToken'];

    //  public function setPasswordAttribute($value){

    //    $this->attributes['password'] = env("PASSWORD_HASH") ? bcrypt($value) : $value;
    //  }

    public function base()
    {

        return $this->belongsTo(TbBase::class, 'idtb_base', 'id');
    }

    public function profile()
    {

        return $this->belongsTo(TbProfile::class, 'idtb_profile', 'id');
    }

    public function launch()
    {

        return $this->belongsTo(Tblaunch::class, 'id_user', 'id');
    }

    public function accesses()
    {
        // Não esqueça de usar a classe Access: use App\Models\Access;
        return $this->hasMany(Access::class);
    }

    public function registerAccess()
    {

        // Cadastra na tabela accesses um novo registro com as informações do usuário logado + data e hora
        return $this->accesses()->create([
            'user_id'   => $this->id,
            'datetime'  => date('YmdHis'),
        ]);
    }

    protected $appends = ['Role', 'RolePermission'];

    // Retorna nome da função
    public function getRoleAttribute()
    {

        $roleId = DB::table('model_has_roles')
            ->where('model_id', $this->id)
            ->get('role_id');


        if (count($roleId) != 0) {
            $roleName = DB::table('roles')
                ->where('id', $roleId[0]->role_id)
                ->get('name')->toArray();
            return $roleName[0]->name;
        } else {
            return 'Indefinida';
        }
    }

    // Retorna nome das permissões
    public function getRolePermissionAttribute()
    {
        $rolePermissions = '';
        $rolePermissionsName = array();
        $roleId = DB::table('model_has_roles')
            ->where('model_id', $this->id)
            ->get('role_id');

        if (count($roleId) != 0) {
            $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
                ->where("role_has_permissions.role_id", $roleId[0]->role_id)
                ->select('name')->get();
        }

        //dd($rolePermissions);
        if ($rolePermissions) {
            foreach($rolePermissions as $name){
               
                array_push($rolePermissionsName, $name->name);
            }

            return $rolePermissionsName;
        } else {
            return 'Indefinidas';
        }
    }
}
