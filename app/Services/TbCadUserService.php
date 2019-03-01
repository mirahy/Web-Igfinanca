<?php

namespace App\Services;

use Exception;
use App\Validators\TbCadUserValidator;
use App\Repositories\TbCadUserRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Database\QueryException;
class TbCadUserService
{

      private $repository;
      private $validator;

      public function __construct(TbCadUserRepository $repository, TbCadUserValidator $validator)
      {
          $this->repository   = $repository;
          $this->validator    = $validator;

      }


      public function store($data)
      {
        try {

              $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);
              $usuario = $this->repository->create($data);



              return [
                'success'     => true,
                'messages'    => 'Usuário '.$usuario['name'].' cadastrado com sucesso!',
                'data'        => $usuario,
              ];


        } catch (Exception $e) {


              switch (get_class($e)) {
                case QueryException::class      : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'erro'  => $e->getMessage()];
                case ValidatorException::class  : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'erro'  => $e->getMessageBag()];
                case Exception::class           : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'erro'  => $e->getMessage()];
                default                         : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'erro'  => $e->getMessage()];
              }

        }

      }



      public function update(){}
      public function delete(){}

}
