<?php

namespace App\Entities;

use Hash;
use App\Entities\Access;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class TbCadUser.
 *
 * @package namespace App\Entities;
 */
class TbCadUser extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     public     $timestamps   = true;
     protected  $table        = 'tb_cad_user';
     protected  $fillable     = ['id','name','idtb_profile','idtb_base','birth','email','password','status','permission','token_access'];
     protected  $hidden       = ['password', 'rememberToken'];

     public function setPasswordAttribute($value)
     {
       $this->attributes['password'] = env("PASSWORD_HASH") ? bcrypt($value) : $value;
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

}
