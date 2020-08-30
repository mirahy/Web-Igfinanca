<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;
use Validator;

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
            'month'       => 'bail|required|not_in:',
            'year'        => 'bail|required|not_in:',

        ],
        ValidatorInterface::RULE_UPDATE => [
            'month'       => 'bail|required|not_in:',
            'year'        => 'bail|required|not_in:',

        ],

    ];
    
    protected $messages = [
        
        'month.required'            => 'Mês deve ser informado!',
        'month.not_in'              => 'Mês deve ser informado!',
        'year.required'             => 'Ano deve ser informado!',
        'year.not_in'               => 'Ano deve ser informado!',
        'year.between'              => 'Informar um ano válido!'
    ];

    public function validaPeriodo($data){
        
        $param = $data['year'].$data['id'];
        $validator = Validator::make($data, 
              ['month'       => "uniqueperiodduple:{$param}"], 
              ['month.uniqueperiodduple' => 'Período já cadastrado!']);
              if($validator->fails()){
                return [
                  'success'     => false,
                  'messages'    => $validator->getMessageBag()->all(),
                  'type'        => $validator->getMessageBag()->keys(),
                ];
              }
              
              return ['success'  => true];
    }

}
