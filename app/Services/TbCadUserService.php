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
              $user = $this->repository->create($data);



              return [
                'success'     => true,
                'messages'    => ['Usuário(a) '.$user['name'].' cadastrado com sucesso!'],
                'data'        => $user,
                'type'        => ["id"],
              ];


        } catch (Exception $e) {

              switch (get_class($e)) {               
                case QueryException::class      : return['success' => false, 'messages' => $e->getMessage(), 'type'  => ["id"]];
                case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
                case Exception::class           : return['success' => false, 'messages' => $e->getMessage(), 'type'  => ["id"]];
                default                         : return['success' => false, 'messages' => $e->getMessage(), 'type'  => ["id"]];
              }

        }

      }



      public function update($data){
        try {

              $id = $data['user_id'];

              $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);
              $user = $this->repository->where($id)->update($data);

              return [
                'success'     => true,
                'messages'    => ['Usuário(a) '.$usuario['name'].' cadastrado com sucesso!'],
                'data'        => $user,
                'type'        => [""],
              ];


        } catch (Exception $e) {

              switch (get_class($e)) {               
                case QueryException::class      : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'erro'  => $e->getMessage()];
                case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
                case Exception::class           : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'erro'  => $e->getMessage()];
                default                         : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'erro'  => $e->getMessage()];
              }

        }
  }


      public function delete($data){

        try {

              $id = $data['user_id'];

              $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);
              $user = $this->repository->where($id)->delete();

              return [
                'success'     => true,
                'messages'    => ['Usuário(a) '.$usuario['name'].' cadastrado com sucesso!'],
                'data'        => $user,
                'type'        => [""],
              ];


        } catch (Exception $e) {

              switch (get_class($e)) {               
                case QueryException::class      : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'erro'  => $e->getMessage()];
                case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
                case Exception::class           : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'erro'  => $e->getMessage()];
                default                         : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'erro'  => $e->getMessage()];
              }

        }
            
      }

}
