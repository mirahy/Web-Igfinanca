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
          'name'             => 'bail|required|min:10',
          'value'            => 'bail|required|numeric|min:0|not_in:0',
          'reference_month'  => 'bail|required|not_in:0',
          'operation_date'   => 'bail|required|date'
        ],

        
        ValidatorInterface::RULE_UPDATE => [
            'name'             => 'bail|required|min:10',
            'value'            => 'bail|required|numeric|min:0|not_in:0',
            'reference_month'  => 'bail|required|not_in:0',
            'operation_date'   => 'bail|required|date'
        ],
    ];

    protected $messages = [
            'name.required'                    => 'Nome deve ser informado!',
            'name.min'                         => 'Nome deve deve ter no mínimo 3 carácteres!',
            'value.required'                   => 'Valor deve ser informado!',
            'value.numeric'                    => 'Valor deve ser numrico!',
            'value.min'                        => 'Valor deve ser maior que zero!',
            'value.not_in'                     => 'Valor deve ser maior que zero!',
            'reference_month.required'         => 'Mês de referência deve ser informado!',
            'reference_month.not_in'           => 'Mês de referência deve ser informado!',
            'operation_date.required'          => 'Data de coleta deve ser informada!',
            'operation_date.date'              => 'Data de coleta deve ser informada!',
    ];
}
