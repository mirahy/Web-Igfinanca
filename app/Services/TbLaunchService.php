<?php

namespace App\Services;

use Exception;
use App\Entities\TbCadUser;
use App\Entities\TbLaunch;
use App\Validators\TbLaunchValidator;
use App\Repositories\TbLaunchRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Database\QueryException;
use DB;
class TbLaunchService
{

      private $repository;
      private $validator;

      public function __construct(TbLaunchRepository $repository, TbLaunchValidator $validator)
      {
          $this->repository   = $repository;
          $this->validator    = $validator;

      }


      public function store($data)
      {
        try {

            

              $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);
              $launch = $this->repository->create($data);
              $msg = $launch['value'];

              return [
                'success'     => true,
                'messages'    => [$msg." adicionado"],
                'data'        => $launch,
                'type'        => ["id"],
              ];


        } catch (Exception $e) {

              switch (get_class($e)) {               
                case QueryException::class      : return['success' => false, 'messages' => $e->getMessage(), 'type'  => ["id"]];
                case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
                case Exception::class           : return['success' => false, 'messages' => $e->getMessage()->all(), 'type'  => ["id"]];
                default                         : return['success' => false, 'messages' => $e->getMessage()->all(), 'type'  => ["id"]];
              }

        }

      }


      public function update($data)
      {
        try {

              $id = $data['id'];

              $this->validator->with($data)->setId($id)->passesOrFail(ValidatorInterface::RULE_UPDATE);
              $launch = $this->repository->update($data, $id);
              $msg = $launch['value'];

              return [
                'success'     => true,
                'messages'    => [$msg." editado"],
                'data'        => $launch,
                'type'        => [""],
              ];


        } catch (Exception $e) {

              switch (get_class($e)) {               
                case QueryException::class      : return['success' => false, 'messages' => $e->getMessage(), 'type'  => $e->getMessage()];
                case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
                case Exception::class           : return['success' => false, 'messages' => $e->getMessage(), 'type'  => $e->getMessage()];
                default                         : return['success' => false, 'messages' => $e->getMessage(), 'type'  => $e->getMessage()];
              }

        }
      }

      public function delete($id)
      {

        try {

              
              $launch = $this->repository->delete($id);
              
          
              return [
                'success'     => true,
                'messages'    => ["excluído"],
                'data'        => $launch,
                'type'        => [""],
              ];


        } catch (Exception $e) {

              switch (get_class($e)) {               
                case QueryException::class      : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
                case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
                case Exception::class           : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
                default                         : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
              }

        }
            
      }


      public function find_IdUser($name)
      {
        
        $data =  TbCadUser::where('name', 'LIKE', '%' . $name. '%')->get();
        
        return  $data;
      }

      public function find_Id($id)
      {

        try {

        $launch = $this->repository->with('user')->find($id)->toArray();
          
          return [
            'success'     => true,
            'messages'    => null,
            'data'        => $launch,
            'type'        => null,
          ];


        } catch (Exception $e) {

              switch (get_class($e)) {               
                case QueryException::class      : return['success' => false, 'messages' => $e->getMessage(), 'type'  => null];
                case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => null];
                case Exception::class           : return['success' => false, 'messages' => $e->getMessage(), 'type'  => null];
                default                         : return['success' => false, 'messages' => $e->getMessage(), 'type'  => null];
              }

        }

      }


      public function aprov($data)
      {
        try {

              $id = $data['id'];

              $launch = TbLaunch::where('id', $id)->update(['status' => $data['status']]);

              
              if(!$launch){
                throw new Exception('');

              } 
              return [
                'success'     => true,
                'messages'    => $data['status'] == 1 ? [" Aprovado"] : [" Reprovado"],
                'data'        => $launch,
                'type'        => [""],
              ];


        } catch (Exception $e) {

              switch (get_class($e)) {               
                case QueryException::class      : return['success' => false, 'messages' => 'Não foi possível aprovar!', 'type'  => $e->getMessage()];
                case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
                case Exception::class           : return['success' => false, 'messages' => 'Não foi possível aprovar!', 'type'  => $e->getMessage()];
                default                         : return['success' => false, 'messages' => 'Não foi possível aprovar!', 'type'  => $e->getMessage()];
              }

        }
      }



}
