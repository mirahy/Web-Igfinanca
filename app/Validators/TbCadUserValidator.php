<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class TbCadUserValidator.
 *
 * @package namespace App\Validators;
 */
class TbCadUserValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
          'name'            => 'bail|required|min:10|unique:tb_cad_user,name',
          'idtb_profile'    => 'required',
          'idtb_base'       => 'required',
          'status'          => 'required',
          'email'           => 'bail|email|min:10|unique:tb_cad_user,email',
          'password'        => 'min:8',
          'Repeatpassword'  => 'bail|not_in:|same:password',
        ],

        
        ValidatorInterface::RULE_UPDATE => [
          'name'            => 'bail|required|min:10|unique:tb_cad_user,name',
          'idtb_profile'    => 'required',
          'idtb_base'       => 'required',
          'status'          => 'required',
          'email'           => 'bail|email|min:10|unique:tb_cad_user,email',
        ],
    ];

    protected $messages = [
      'name.required'             => 'Nome deve ser informado!',
      'name.min'                  => 'Nome deve conter no minimo 10 carácteres!',
      'name.unique'               => 'Nome já registrado!',
      'email.required'            => 'Email deve ser informado!',
      'email.min'                 => 'Email deve conter no minimo 10 carácteres!',
      'email.unique'              => 'Email já registrado!',
      'email.email'               => 'Informar um email válido ex. email@email.com!',
      'password.required'         => 'Senha deve ser informada!',
      'password.min'              => 'Senha deve conter no minimo 8 carácteres!',
      'Repeatpassword.required'   => 'Confirme sua senha!',
      'Repeatpassword.not_in'   => 'Confirme sua senha!',
      'Repeatpassword.same'       => 'As senhas deven ser iguais!',
      'idtb_profile.required'     => 'Selecionar Perfil',
      'idtb_base.required'        => 'Selecionar Base',
      'status.required'           => 'Selecionar Status',
    ];
}
