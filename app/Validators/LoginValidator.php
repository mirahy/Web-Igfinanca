<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class TbCadUserValidator.
 *
 * @package namespace App\Validators;
 */
class LoginValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
          'email'                      => 'bail|required|min:10|email',
          'password'                   => 'bail|required|min:5',
          //'g-recaptcha-response'       => 'required',

        ],
        ValidatorInterface::RULE_UPDATE => [],
      ];

      protected $messages = [
        'email.required' => 'Email e/ou senha inválidos!',
        'email.min'  => 'Email e/ou senha inválidos!',
        'email.email' => 'Email e/ou senha inválidos!',
        'password.required' => 'Email e/ou senha inválidos!',
        'password.min' => 'Email e/ou senha inválidos!',
        'g-recaptcha-response.required' => 'O campo recaptcha é obrigatório!',
        
    ];
    
}
