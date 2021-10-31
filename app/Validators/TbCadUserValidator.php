<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;
use Validator;

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
          'name'                    => 'bail|required|min:2|unique:tb_cad_user,name',
          'idtb_profile'            => 'bail|required',
          'idtb_base'               => 'bail|required',
          'status'                  => 'bail|required',
          'email'                   => 'bail|nullable|email|min:10|unique:tb_cad_user,email',
          'password'                => 'bail|nullable|required_with:password_confirmation|min:8',
          'password_confirmation'   => 'bail|nullable|required_with:password'
        ],

        
        ValidatorInterface::RULE_UPDATE => [
          'name'            => 'bail|required|min:2|unique:tb_cad_user,name',
          'idtb_profile'    => 'required',
          'idtb_base'       => 'required',
          'status'          => 'required',
          'email'           => 'bail|nullable|email|min:10|unique:tb_cad_user,email',
        ],
    ];

    protected $messages = [
      'name.required'                         => 'Nome deve ser informado!',
      'name.min'                              => 'Nome deve conter no minimo 10 carácteres!',
      'name.unique'                           => 'Nome já registrado!',
      'email.required'                        => 'Email deve ser informado!',
      'email.min'                             => 'Email deve conter no minimo 10 carácteres!',
      'email.unique'                          => 'Email já registrado!',
      'email.email'                           => 'Informar um email válido ex. email@email.com!',
      'password.min'                          => 'Senha deve conter no minimo 8 carácteres!',
      'password.required_with'                => 'Senha deve ser informada!',
      'password.confirmed'                    => 'As senhas devem ser iguais!',
      'password_confirmation.required_with'   => 'Repita a senha!',
      'idtb_profile.required'                 => 'Selecionar Perfil',
      'idtb_base.required'                    => 'Selecionar Base',
      'status.required'                       => 'Selecionar Status',
    ];

     //verifica se lançamento esta dentro do período
     public function validaInputsPass($data){

      $param = $data['password'];
      $param2 = $data['password_confirmation'];

      $validator = Validator::make($data, 
            ['password'       => "pass_validadte:$param,$param2"], 
            ['password.pass_validadte' => "As senhas devem ser iguais!"]);

            if($validator->fails()){
              return [
                'success'     => false,
                'messages'    => ['As senhas devem ser iguais!','As senhas devem ser iguais!'],
                'type'        => ['password','password_confirmation'],
              ];
            }
            
            return ['success'  => true];
  }
}
