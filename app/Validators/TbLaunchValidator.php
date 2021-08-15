<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;
use Validator;

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
          'name'              => 'bail|required|min:10',
          'value'             => 'bail|required|numeric|min:0|not_in:0',
          'idtb_closing'      => 'bail|required|not_in:0',
          'operation_date'    => 'bail|required|date',
          'idtb_payment_type' => 'bail|required|not_in:0'
        ],

        
        ValidatorInterface::RULE_UPDATE => [
            'name'             => 'bail|required|min:10',
            'value'            => 'bail|required|numeric|min:0|not_in:0',
            'idtb_closing'     => 'bail|required|not_in:0',
            'operation_date'   => 'bail|required|date',
            'idtb_payment_type' => 'bail|required|not_in:0'
        ],
    ];

    protected $messages = [
            'name.required'                    => 'Nome deve ser informado!',
            'name.min'                         => 'Nome deve deve ter no mínimo 3 carácteres!',
            'value.required'                   => 'Valor deve ser informado!',
            'value.numeric'                    => 'Valor deve ser numrico!',
            'value.min'                        => 'Valor deve ser maior que zero!',
            'value.not_in'                     => 'Valor deve ser maior que zero!',
            'idtb_closing.required'            => 'Mês de referência deve ser informado!',
            'idtb_closing.not_in'              => 'Mês de referência deve ser informado!',
            'operation_date.required'          => 'Data de coleta deve ser informada!',
            'operation_date.date'              => 'Data de coleta deve ser informada!',
            'idtb_payment_type.required'       => 'Tipo de pagamento deve ser informado!',
            'idtb_payment_type.not_in'         => 'Tipo de pagamento deve ser informado!',
    ];


    //verifica se lançamento esta dentro do período
    public function validaDataPeriodo($closing, $data){
        
        $param = $closing['0']['InitPeriod'].$closing['0']['FinalPeriod'];
        $init = new \DateTime($closing['0']['InitPeriod']);
        $final = new \DateTime($closing['0']['FinalPeriod']);
        $msg = 'Data fora do período permitido ('.$init->format( 'd-m-Y').' a '.$final->format( 'd-m-Y').')!';

        $validator = Validator::make($data, 
              ['operation_date'       => "period:{$param}"], 
              ['operation_date.period' => $msg]);

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
