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
          'name'            => 'required|min:10',
          'email'           => 'required|email|min:10',
          'password'        => 'required|min:8',
          'Repeatpassword'  => 'required|same:password',
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
