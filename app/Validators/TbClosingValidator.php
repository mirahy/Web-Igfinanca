<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class TbClosingValidator.
 *
 * @package namespace App\Validators;
 */
class TbClosingValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'month'       => 'required',
            'year'        => 'required',

        ],
        ValidatorInterface::RULE_UPDATE => [
            'month'       => 'required',
            'year'        => 'required',

        ],
    ];

    protected $messages = [
        'month.required'            => 'Mês deve ser informado!',
        'year.required'             => 'Ano deve ser informado!',
        
      ];
}
