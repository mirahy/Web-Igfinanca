<?php

namespace App\Services;

use Exception;
use App\Entities\TbClosing;
use App\Validators\TbClosingValidator;
use App\Repositories\TbClosingRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Database\QueryException;
use Yajra\Datatables\Datatables;
use DB;

class TbClosingsService
{

      private $repository;
      private $validator;
      private $serviceUser;

      public function __construct(TbClosingRepository $repository, TbClosingValidator $validator )
      {
          $this->repository   = $repository;
          $this->validator    = $validator;
          
      }

      //função cadastar período
      public function store($data)
      {
        try {
              // validando campos
              $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);
              // validando se período ja existe
              $v = $this->validator->validaPeriodo($data);
              if(!$v['success']){
                return $v;
              }
              

              $closing = $this->repository->create($data);

              $month = $closing['month'];

              return [
                'success'     => true,
                'messages'    => [$month." adicionado"],
                'data'        => $closing,
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

      //função atualizar período
      public function update($data)
      {
         try {
 
               $id = $data['id'];
                // validando campos
               $this->validator->with($data)->setId($id)->passesOrFail(ValidatorInterface::RULE_UPDATE);
               // validando se período ja existe
               $v = $this->validator->validaPeriodo($data);
              if(!$v['success']){
                return $v;
              }

               $closing = $this->repository->update($data, $id);
 
               $month = $closing['month'];
 
               return [
                 'success'     => true,
                 'messages'    => [$month." editado"],
                 'data'        => $closing,
                 'type'        => [""],
               ];
 
 
         } catch (Exception $e) {
          dd($e);
               switch (get_class($e)) {               
                 case QueryException::class      : return['success' => false, 'messages' => 'Não foi possivel cadastar o período!', 'type'  => $e->getMessage()];
                 case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
                 case Exception::class           : return['success' => false, 'messages' => 'Não foi possivel cadastar o período!', 'type'  => $e->getMessage()];
                 default                         : return['success' => false, 'messages' => 'Não foi possivel cadastar o período!', 'type'  => $e->getMessage()];
               }
 
         }
      }

      //retorna periodo pelo id
      public function find_Id($id)
      {

        try {

         $closing = $this->repository->find($id)->toArray();

          return [
            'success'     => true,
            'messages'    => null,
            'data'        => $closing,
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


      //retorna Fechamentos para uso na tabelas do framework datatables
      public function find_DataTables($request)
      {

        $def = '%';

            return  Datatables::of(TbClosing::query()
                                    ->where([['month', 'LIKE', $request->query('month', $def)],
                                             ['year', 'LIKE', $request->query('year', $def)],
                                             ['status', 'LIKE', $request->query('status', $def)]])
                                    ->orderByRaw('month','year'))
                                    ->blacklist(['action'])
                                    ->make(true);
      }

       //função deletar lançamento
       public function delete($id)
       {
 
         try {

                $v = $this->validator->countLaunch($id);
                if(!$v['success']){
                  return $v;
                }
     
               $closing = $this->repository->delete($id);
               
               return [
                 'success'     => true,
                 'messages'    => "",
                 'data'        => $closing,
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


}