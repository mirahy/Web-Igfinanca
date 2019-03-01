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
          'name'            => 'required',
          'idtb_profile'    => 'required',
          'idtb_base'       => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
