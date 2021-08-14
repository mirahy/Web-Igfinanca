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

              $closing = $this->repository->with('closing')->find($id)->toArray();
              
              if($closing['closing']['status'] == 0){
                return[
                       'success'  => 'closing',
                       'messages' => 'Lançamento com período fechado não pode ser editado!',
                       'type'     => 'reference_month',
                       'data'     => '',
                      ];

              }
              
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

        $launch = $this->repository->with('user')
                                   ->with('closing')
                                   ->with('payment_type')
                                   ->find($id)
                                   ->toArray();
          
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

              $closing = $this->repository->with('closing')->find($id)->toArray();
              
              if($closing['closing']['status'] == 0){
                return[
                       'success'  => false,
                       'messages' => 'Lançamento com período fechado não pode ser Aprovado/Reprovado!',
                       'type'     => '',
                       'data'     => '',
                      ];

              }

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
                                    ->with('closing')
                                    ->with('payment_type')
                                    ->orwhereHas('closing', function($q) use ($request, $def)
                                                {   
                                                    $q->where([['status', 'like',  $request->query('closing_status', $def)],
                                                               ['id', 'like',  $request->query('reference_month', $def)]])
                                                      ->orWhere('status', 'like',  $request->query('closing_status1', $def));

                                                })
                                                
                                    ->where([['idtb_type_launch', 'LIKE', $request->query('launch', $def)],
                                             ['status', 'LIKE', $request->query('status', $def)],
                                             ['idtb_caixa', 'LIKE', $request->query('caixa', $def)],
                                             ['idtb_operation', 'LIKE', $request->query('operation', $def)]])
                                    ->orderBy('operation_date'))
                                    ->blacklist(['action'])
                                    ->make(true);
      }

       //retorna lançamentos via parametros passados
      public function find_Parameters($request)
      {
          $def = '%';

         // dd($request);

          if ($request->isMethod('get')) {
              return  (TbLaunch::query()
                        ->with('user')
                        ->with('caixa')
                        ->with('type_launch')
                        ->with('closing')
                        ->with('payment_type')
                        ->whereHas('closing', function($q) use ($request, $def)
                                  {   
                                      $q->where([['status', 'like',  $request->query('closing_status', $def)],
                                                 ['id', 'like',  $request->query('reference_month', $def)]]);

                                  })
                        ->where([['idtb_type_launch', 'LIKE', $request->query('launch', $def)], 
                                ['status', 'LIKE', $request->query('status', $def)],
                                ['idtb_caixa', 'LIKE', $request->query('caixa', $def)],
                                ['idtb_closing', 'LIKE', $request->query('reference_month', $def)],
                                ['idtb_operation', 'LIKE', $request->query('operation', $def)]])
                        ->orderBy('operation_date'))
                        ->get();

        }elseif ($request->isMethod('post')) {
                  return  (TbLaunch::query()
                            ->with('user')
                            ->with('caixa')
                            ->with('type_launch')
                            ->with('closing')
                            ->with('payment_type')
                            ->whereHas('closing', function($q) use ($request)
                                        {   
                                            $q->where([['status', 'like',  $request['closing_status']],
                                            ['id', 'like',  $request['reference_month']]]);

                                        })
                            ->where([['idtb_type_launch', 'LIKE', $request['launch']], 
                                    ['status', 'LIKE', $request['status']],
                                    ['idtb_caixa', 'LIKE', $request['caixa']],
                                    ['idtb_closing', 'LIKE', $request['reference_month']],
                                    ['idtb_operation', 'LIKE', $request['operation']]])
                            ->orderBy('operation_date'))
                            ->get();

        }

      }

       //retorna valor dos lançamentos via parametros passados
      public function Sum($request)
      {
         $def = '%';

         

         if ($request->isMethod('get')) {
             return (TbLaunch::query()
                      ->whereHas('closing', function($q) use ($request, $def)
                                  {   
                                      $q->where([['status', 'like',  $request->query('closing_status', $def)],
                                                 ['id', 'like',  $request->query('reference_month', $def)]]);

                                  })
                      ->where([['idtb_type_launch', 'LIKE', $request->query('launch', $def)], 
                               ['status', 'LIKE', $request->query('status', $def)],
                               ['idtb_caixa', 'LIKE', $request->query('caixa', $def) ],
                               ['idtb_operation', 'LIKE', $request->query('operation', $def)]]))
                               ->sum('value');             

         }elseif ($request->isMethod('post')) {
                  return  (TbLaunch::query()
                             ->whereHas('closing', function($q) use ($request)
                                        {   
                                            $q->where([['status', 'like',  $request['closing_status']],
                                            ['id', 'like',  $request['reference_month']]]);

                                        })
                            ->where([['idtb_type_launch', 'LIKE', $request['launch']], 
                                    ['status', 'LIKE', $request['status']],
                                    ['idtb_caixa', 'LIKE', $request['caixa']],
                                    ['idtb_operation', 'LIKE', $request['operation']]]))
                            ->sum('value');

         }

      }

       //retorna lançamentos pendentes via parametros passados
       public function Pend($request)
       {
 
         $def = '%';
 
             return  (TbLaunch::query()
                      ->where([['idtb_type_launch', 'LIKE', $request->query('launch', $def)], 
                               ['status', 'LIKE', $request->query('status', $def)],
                               ['idtb_caixa', 'LIKE', $request->query('caixa', $def) ],
                               ['idtb_operation', 'LIKE', $request->query('operation', $def)]]))
                      ->count();
       }

       //retorna numero do mes
       public function number_month($string){

        switch ($string) {
          case "Janeiro":      $mes = '01';   break;
          case "Fevereiro":    $mes = '02';   break;
          case "Março":        $mes = '03';   break;
          case "Abril":        $mes = '04';   break;
          case "Maio":         $mes = '05';   break;
          case "Junho":        $mes = '06';   break;
          case "Julho":        $mes = '07';   break;
          case "Agosto":       $mes = '08';   break;
          case "Setembro":     $mes = '09';   break;
          case "Outubro":      $mes = '10';   break;
          case "Novembro":     $mes = '11';   break;
          case "Dezembro":     $mes = '12';   break;
          default:             $mes = 'erro';
        }
        return $mes;
       }


      



}
