<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class TbLaunchValidator.
 *
 * @package namespace App\Validators;
 */
class TbLaunchValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
          'name'             => 'required|min:3',
          'value'            => 'required',
          'reference_month'  => 'required',
        ],

        
        ValidatorInterface::RULE_UPDATE => [
            'name'             => 'required|min:5',
            'value'            => 'required',
            'reference_month'  => 'required',
        ],
    ];

    protected $messages = [
            'name.required'             => 'Nome deve ser informado!',
            'name.min'                  => 'Nome deve deve ter no mínimo 3 carácteres!',
            'value.required'                     => 'Valor deve ser informado!',
            'reference_month.required'           => 'Mês de referência deve ser informado!',
    ];
}
