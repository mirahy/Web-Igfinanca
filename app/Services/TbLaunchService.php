<?php

namespace App\Services;

use Exception;
use App\Entities\TbLaunch;
use App\Services\TbCadUserService;
use App\Validators\TbLaunchValidator;
use App\Repositories\TbLaunchRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Database\QueryException;
use Yajra\Datatables\Datatables;
use DB;
class TbLaunchService
{

      private $repository;
      private $validator;
      private $serviceUser;

      public function __construct(TbLaunchRepository $repository, TbLaunchValidator $validator, TbCadUserService $serviceUser )
      {
          $this->repository   = $repository;
          $this->validator    = $validator;
          $this->serviceUser  = $serviceUser;

      }

      //função cadastar lançamento
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

      //função atualizar lançamento
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

      //função deletar lançamento
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

      //retorna usuário pelo nome
      public function find_User_name($name)
      {
        
        return  $this->serviceUser->find_User_name($name);
      }

      //retorna lançamento pelo id
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

      //função aprovar/reprovar lançamentos
      public function aprov_id($data)
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

      //retorna lançamentos para uso na tabelas do framework datatables
      public function find_DataTables($request)
      {

        $def = '%';

            return  Datatables::of(TbLaunch::query()
                                    ->with('user')
                                    ->with('caixa')
                                    ->with('type_launch')
                                    ->where([['idtb_type_launch', 'LIKE', $request->query('launch', $def)],
                                             ['status', 'LIKE', $request->query('status', $def)],
                                             ['idtb_caixa', 'LIKE', $request->query('caixa', $def)],
                                             ['idtb_operation', 'LIKE', $request->query('operation', $def)],
                                             ['reference_month', 'LIKE', $request->query('month', $def)],
                                             ['reference_year', 'LIKE', $request->query('year', $def)]])
                                    ->orderBy('operation_date'))
                                    ->blacklist(['action'])
                                    ->make(true);
      }

       //retorna lançamentos via parametros passados
       public function find_Parameters($request)
       {
 
         $def = '%';
         $now = now();
 
             return  (TbLaunch::query()
                      ->with('user')
                      ->with('caixa')
                      ->with('type_launch')
                      ->where([['idtb_type_launch', 'LIKE', $request->query('launch', $def)], 
                               ['status', 'LIKE', $request->query('status', $def)],
                               ['idtb_caixa', 'LIKE', $request->query('caixa', $def) ],
                               ['idtb_operation', 'LIKE', $request->query('operation', $def)],
                               ['reference_month', 'LIKE', $request->query('month', $def)],
                               ['reference_year', 'LIKE', $request->query('year', $def)]])
                      ->orderBy('operation_date'))
                      ->get();
       }

       //retorna valor dos lançamentos via parametros passados
       public function Sum($request)
       {
 
         $def = '%';
 
             return  (TbLaunch::query()
                      ->where([['idtb_type_launch', 'LIKE', $request->query('launch', $def)], 
                               ['status', 'LIKE', $request->query('status', $def)],
                               ['idtb_caixa', 'LIKE', $request->query('caixa', $def) ],
                               ['idtb_operation', 'LIKE', $request->query('operation', $def)],
                               ['reference_month', 'LIKE', $request->query('month', $def)],
                               ['reference_year', 'LIKE', $request->query('year', $def)]]))
                      ->sum('value');
       }

       //retorna lançamentos pendentes via parametros passados
       public function Pend($request)
       {
 
         $def = '%';
 
             return  (TbLaunch::query()
                      ->where([['idtb_type_launch', 'LIKE', $request->query('launch', $def)], 
                               ['status', 'LIKE', $request->query('status', $def)],
                               ['idtb_caixa', 'LIKE', $request->query('caixa', $def) ],
                               ['idtb_operation', 'LIKE', $request->query('operation', $def)],
                               ['reference_month', 'LIKE', $request->query('month', $def)],
                               ['reference_year', 'LIKE', $request->query('year', $def)]]))
                      ->count();
       }


      



}
