<?php

namespace App\Services;

use Exception;
use App\Entities\TbCadUser;
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


              return [
                'success'     => true,
                'messages'    => [$launch['value']],
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


      public function find_IdUser($name){
        
        $data =  TbCadUser::where('name', 'LIKE', '%' . $name. '%')->get();
        
        return  $data;
  }





}
