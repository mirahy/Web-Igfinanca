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
          'email'                      => 'required|min:10|email',
          'password'                   => 'required|min:5',
          'g-recaptcha-response'       => 'required',

        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
