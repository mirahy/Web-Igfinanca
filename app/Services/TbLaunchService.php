<?php

namespace App\Services;

use Exception;
use App\Entities\TbLaunch;
use App\Entities\TbClosing;
use App\Services\TbCadUserService;
use App\Validators\TbLaunchValidator;
use App\Repositories\TbLaunchRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Database\QueryException;
use Yajra\Datatables\Datatables;
use DB;
use App\Http\Controllers\ConnectDbController;
use Illuminate\Support\Arr;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;



class TbLaunchService
{
  use LogsActivity;

  private $repository;
  private $validator;
  private $serviceUser;
  private $ConnectDbController;

  public function __construct(
    TbLaunchRepository $repository,
    TbLaunchValidator $validator,
    TbCadUserService $serviceUser,
    ConnectDbController $ConnectDbController
  ) {
    $this->repository           = $repository;
    $this->validator            = $validator;
    $this->serviceUser          = $serviceUser;
    $this->ConnectDbController  = $ConnectDbController;
  }

  //função cadastar lançamento
  public function store($data)
  {
    try {


      // validando campos
      $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);

      // validando se periodo ja esta fechado
      $idClosing = $data['idtb_closing'];
      $closing = TbClosing::Where('id', $idClosing)->get();
      if ($closing['0']['status'] == 0) {
        return [
          'success'  => false,
          'messages' => 'Período fechado não pode receber lançamentos!',
          'type'     => 'reference_month',
          'data'     => '',
        ];
      }

      //verifica se é para validar o período e se lançamento esta dentro do peíodo
      if ($closing[0]['period_valid']) {
        $v = $this->validator->validaDataPeriodo($closing, $data);
        if (!$v['success']) {
          return $v;
        }
      }



      // registra lançamento no banco de dados filial
      $launch = $this->repository->create($data);
      //recupera id gerado na base filial para inserir na coluna id_filial na base matriz
      $data['id_filial'] = $launch['id'];
      //altera a conexão para base matriz
      $this->ConnectDbController->connectMatriz();
      //destivando registro de log para as replicações
      activity()->disableLogging();
      // registra lançamento no banco de dados matriz
      $launch_mtz = $this->repository->create($data);
      //ativando registro de log
      activity()->enableLogging();
      //remove a chave e valor de id_filias, insere id filial e recupera id gerado na base matriz para inserir na coluna id_mtz na base filial
      Arr::forget($data, 'id_filial');
      $data['id'] = $launch['id'];
      $data['id_mtz'] = $launch_mtz['id'];
      //altera a conexão para base Local
      $this->ConnectDbController->connectBase();
      // atualiza a coluna id_mtz na base filial
      $this->repository->update($data, $launch['id']);
      

      $msg = $launch['value'];

      return [
        'success'     => true,
        'messages'    => [$msg . " adicionado"],
        'data'        => $launch,
        'type'        => ["id"],
      ];
    } catch (Exception $e) {
      
      switch (get_class($e)) {
        case QueryException::class:
          return ['success' => false, 'messages' => $e->getMessage(), 'type'  => ["id"]];
        case ValidatorException::class:
          return ['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
        case Exception::class:
          return ['success' => false, 'messages' => $e->getMessage()->all(), 'type'  => ["id"]];
        default:
          return ['success' => false, 'messages' => $e->getMessage()->all(), 'type'  => ["id"]];
      }
    }
  }

  //função atualizar lançamento
  public function update($data)
  {
    try {


      $id = $data['id'];
      // validando campos
      $this->validator->with($data)->setId($id)->passesOrFail(ValidatorInterface::RULE_UPDATE);

      // validando se periodo ja esta fechado
      $idClosing = $data['idtb_closing'];
      $closing = TbClosing::Where('id', $idClosing)->get();
      if ($closing['0']['status'] == 0) {
        return [
          'success'  => false,
          'messages' => 'Período fechado não pode receber lançamentos!',
          'type'     => 'reference_month',
          'data'     => '',
        ];
      }

      //verifica se é para validar o período e se lançamento esta dentro do peíodo
      if ($closing[0]['period_valid']) {
        $v = $this->validator->validaDataPeriodo($closing, $data);
        if (!$v['success']) {
          return $v;
        }
      }


      //atualiza lançamento no banco de dados filial
      $launch = $this->repository->update($data, $id);
      //recupera id_mtz
      $data['id'] = $launch['id_mtz'];
      $id_mtz = $launch['id_mtz'];
      //altera a conexão para base matriz
      $this->ConnectDbController->connectMatriz();
      //destivando registro de log para as replicações
      activity()->disableLogging();
      // atualiza lançamento no banco de dados matriz
      $launch = $this->repository->update($data, $id_mtz);
      //ativando registro de log
      activity()->enableLogging();
      //altera a conexão para base Local
      $this->ConnectDbController->connectBase();



      $msg = $launch['value'];

      return [
        'success'     => true,
        'messages'    => [$msg . " editado"],
        'data'        => $launch,
        'type'        => [""],
      ];
    } catch (Exception $e) {


      switch (get_class($e)) {
        case QueryException::class:
          return ['success' => false, 'messages' => $e->getMessage(), 'type'  => $e->getMessage()];
        case ValidatorException::class:
          return ['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
        case Exception::class:
          return ['success' => false, 'messages' => $e->getMessage(), 'type'  => $e->getMessage()];
        default:
          return ['success' => false, 'messages' => $e->getMessage(), 'type'  => $e->getMessage()];
      }
    }
  }

  //função deletar lançamento
  public function delete($id)
  {

    try {

      //recupera id_mtz
      $data = $this->repository->find($id);
      //exclui o registro da base local
      $launch = $this->repository->delete($id);
      //altera a conexão para base matriz
      $this->ConnectDbController->connectMatriz();
      //destivando registro de log para as replicações
      activity()->disableLogging();
      //exclui o registro da base matriz
      $launch_mtz = $this->repository->delete($data['id_mtz']);
      //ativando registro de log
      activity()->enableLogging();
      //altera a conexão para base Local
      $this->ConnectDbController->connectBase();

      $msg = $data['value'];

      return [
        'success'     => true,
        'messages'    => [$msg . " excluído"],
        'data'        => $launch,
        'type'        => [""],
      ];
    } catch (Exception $e) {

      switch (get_class($e)) {
        case QueryException::class:
          return ['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
        case ValidatorException::class:
          return ['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
        case Exception::class:
          return ['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
        default:
          return ['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
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
        case QueryException::class:
          return ['success' => false, 'messages' => $e->getMessage(), 'type'  => null];
        case ValidatorException::class:
          return ['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => null];
        case Exception::class:
          return ['success' => false, 'messages' => $e->getMessage(), 'type'  => null];
        default:
          return ['success' => false, 'messages' => $e->getMessage(), 'type'  => null];
      }
    }
  }

  //função aprovar/reprovar lançamentos
  public function aprov_id($data)
  {
    try {

      $id       = $data['id'];
      $launch   = TbLaunch::where('id', $id)->get();
      $id_mtz   = $launch[0]['id_mtz'];
      $launch[0]['status'] = $data['status'];
      $launch[0]['name'] = DB::table('tb_cad_user')->where('id', $launch[0]['id_user'])->get('name')->toArray();
      $launch[0]['name'] = $launch[0]['name'][0]->name;




      $closing = $this->repository->with('closing')->find($id)->toArray();

      if ($closing['closing']['status'] == 0) {
        return [
          'success'  => false,
          'messages' => 'Lançamento com período fechado não pode ser Aprovado/Reprovado!',
          'type'     => '',
          'data'     => '',
        ];
      }

      $launch = $launch->toArray();

      //atualiza lançamento no banco de dados filial
      $launch_filial = $this->repository->update($launch[0], $id);
      //altera a conexão para base matriz
      $this->ConnectDbController->connectMatriz();
      unset($launch[0]['id_mtz']);
      $launch[0]['id'] = $id_mtz;
      $launch[0]['id_filial'] = $id;
      //destivando registro de log para as replicações
      activity()->disableLogging();
      //Atualiza campo status na base matriz
      $launch = $this->repository->update($launch[0], $id_mtz);
      //ativando registro de log
      activity()->enableLogging();
      //altera a conexão para base Local
      $this->ConnectDbController->connectBase();


      if (!$launch) {
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
        case QueryException::class:
          return ['success' => false, 'messages' => 'Não foi possível aprovar!', 'type'  => $e->getMessage()];
        case ValidatorException::class:
          return ['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
        case Exception::class:
          return ['success' => false, 'messages' => 'Não foi possível aprovar!', 'type'  => $e->getMessage()];
        default:
          return ['success' => false, 'messages' => 'Não foi possível aprovar!', 'type'  => $e->getMessage()];
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
      ->with('operation')
      ->with('base')
      ->orwhereHas('closing', function ($q) use ($request, $def) {
        $q->where([
          ['status', 'like',  $request->query('closing_status', $def)],
          ['id', 'like',  $request->query('reference_month', $def)]
        ])
          ->orWhere('status', 'like',  $request->query('closing_status1', $def));
      })

      ->where([
        ['idtb_type_launch', 'LIKE', $request->query('launch', $def)],
        ['status', 'LIKE', $request->query('status', $def)],
        ['idtb_caixa', 'LIKE', $request->query('caixa', $def)],
        ['idtb_operation', 'LIKE', $request->query('operation', $def)]
      ])
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
      return (TbLaunch::query()
        ->with('user')
        ->with('caixa')
        ->with('type_launch')
        ->with('closing')
        ->with('payment_type')
        ->whereHas('closing', function ($q) use ($request, $def) {
          $q->where([
            ['status', 'like',  $request->query('closing_status', $def)],
            ['id', 'like',  $request->query('reference_month', $def)]
          ]);
        })
        ->where([
          ['idtb_type_launch', 'LIKE', $request->query('launch', $def)],
          ['status', 'LIKE', $request->query('status', $def)],
          ['idtb_caixa', 'LIKE', $request->query('caixa', $def)],
          ['idtb_closing', 'LIKE', $request->query('reference_month', $def)],
          ['idtb_operation', 'LIKE', $request->query('operation', $def)]
        ])
        ->orderBy('operation_date'))
        ->get();
    } elseif ($request->isMethod('post')) {
      return (TbLaunch::query()
        ->with('user')
        ->with('caixa')
        ->with('type_launch')
        ->with('closing')
        ->with('payment_type')
        ->whereHas('closing', function ($q) use ($request, $def) {
          $q->where([
            ['status', 'like',  $request->has('closing_status') ? $request['closing_status'] : $def],
            ['id', 'like',  $request->has('reference_month') ? $request['reference_month'] : $def]
          ]);
        })
        ->where([
          ['idtb_type_launch', 'LIKE', $request['launch']],
          ['status', 'LIKE', $request['status']],
          ['idtb_caixa', 'LIKE', $request['caixa']],
          ['idtb_closing', 'LIKE', $request['reference_month']],
          ['idtb_operation', 'LIKE', $request['operation']]
        ])
        ->orderBy('operation_date'))
        ->get();
    }
  }

  //retorna valor dos lançamentos via parametros passados
  public function Sum($request)
  {
    $def = '%';


    if ($request->isMethod('get')) {
      $value = TbLaunch::query()
        ->whereHas('closing', function ($q) use ($request, $def) {
          $q->where([
            ['status', 'like',  $request->query('closing_status', $def)],
            ['id', 'like',  $request->query('reference_month', $def)]
          ]);
        })
        ->where([
          ['idtb_type_launch', 'LIKE', $request->query('launch', $def)],
          ['status', 'LIKE', $request->query('status', $def)],
          ['idtb_caixa', 'LIKE', $request->query('caixa', $def)],
          ['idtb_operation', 'LIKE', $request->query('operation', $def)]
        ])
        ->sum('value');

      return floatval($value);
    } elseif ($request->isMethod('post')) {

      $value = TbLaunch::query()
        ->whereHas('closing', function ($q) use ($request, $def) {
          $q->where([
            ['status', 'like',  $request->has('closing_status') ? $request['closing_status'] : $def],
            ['id', 'like',  $request->has('reference_month') ? $request['reference_month'] : $def]
          ]);
        })
        ->where([
          ['idtb_type_launch', 'LIKE', $request['launch']],
          ['status', 'LIKE', $request['status']],
          ['idtb_caixa', 'LIKE', $request['caixa']],
          ['idtb_operation', 'LIKE', $request['operation']]
        ])
        ->sum('value');

      return floatval($value);
    }
  }

  //retorna lançamentos pendentes via parametros passados
  public function Pend($request)
  {

    $def = '%';

    if($request->isMethod('get')){

      return (TbLaunch::query()
        ->where([
          ['idtb_type_launch', 'LIKE', $request->query('launch', $def)],
          ['status', 'LIKE', $request->query('status', $def)],
          ['idtb_caixa', 'LIKE', $request->query('caixa', $def)],
          ['idtb_operation', 'LIKE', $request->query('operation', $def)]
        ]))
        ->count();
    }elseif($request->isMethod('post')){

      return (TbLaunch::query()
        ->where([
          ['idtb_type_launch', 'LIKE', $request->has('launch') ? $request['launch'] : $def],
          ['status', 'LIKE', $request->has('status') ? $request['status'] : $def],
          ['idtb_caixa', 'LIKE', $request->has('caixa') ? $request['caixa'] : $def],
          ['idtb_operation', 'LIKE', $request->has('operation') ? $request['operation'] : $def]
        ]))
        ->count();
    }

  }

  //retorna numero do mes
  static public function number_month($string)
  {

    switch (strtolower($string)) {
      case "janeiro":
        $mes = '01';
        break;
      case "fevereiro":
        $mes = '02';
        break;
      case "março":
        $mes = '03';
        break;
      case "abril":
        $mes = '04';
        break;
      case "maio":
        $mes = '05';
        break;
      case "junho":
        $mes = '06';
        break;
      case "julho":
        $mes = '07';
        break;
      case "agosto":
        $mes = '08';
        break;
      case "setembro":
        $mes = '09';
        break;
      case "outubro":
        $mes = '10';
        break;
      case "novembro":
        $mes = '11';
        break;
      case "dezembro":
        $mes = '12';
        break;
      default:
        $mes = 'erro';
    }
    return $mes;
  }

  //retorna de valores para saldo inicial do período(para datas anteriores a 01/08/2021 retorna zero)
  public function saldo($request)
  {

    $closing = TbClosing::Where('status', 1)->count();

   

    if (!$closing) {
      return 0;
    } else {
      // se passado o parametro refence_month, pega o fechamento referente se não pega o fechamento em aberto
      $closing = $request->only('reference_month') ? TbClosing::Where('id', $request->only('reference_month'))->get() : TbClosing::Where('status', 1)->get();
      // parametro refence_month removido par não influenciar no resultado
      $request->request->remove('reference_month');
      $initPeriod = new \DateTime($closing[0]['InitPeriod']);
      $initPeriod->modify('-1 day');

      if ($request->only('closing_id')) {
        // se passado o valor closig_id, sera retornado o saldo referente ao periodo em aberto sem validar a data
        $request->request->add(['reference_month' => $closing[0]['id']]);
        // se passado os parametros init e finalDate pega o saldo do fechamento senão pega o saldo anterior ao fechamento
        $initDate =  new \DateTime('2021/01/01');
        $finalDate = $request->has('finalDate') ? new \DateTime($closing[0]['FinalPeriod']) : $initPeriod->format('Y-m-d');
      } else {
        // se passado os parametros init e finalDate pega o saldo do fechamento senão pega o saldo anterior ao fechamento
        $initDate = $request->has('initDate') ? new \DateTime($closing[0]['InitPeriod']) : new \DateTime('2021/01/01');
        $finalDate = $request->has('finalDate') ? new \DateTime($closing[0]['FinalPeriod']) : $initPeriod->format('Y-m-d');
      }


      $def = '%';

      if ($request->isMethod('get')) {

        $entradas = TbLaunch::query()
          ->whereHas('closing', function ($q) use ($request, $def) {
            $q->where([
              ['status', 'like',  $request->query('closing_status', $def)],
              ['id', 'like',  $request->query('reference_month', $def)]
            ]);
          })
          ->where([
            ['idtb_type_launch', 'LIKE', $request->query('launch', $def)],
            ['status', 'LIKE', $request->query('status', $def)],
            ['idtb_caixa', 'LIKE', $request->query('caixa', $def)],
            ['idtb_operation', 'LIKE', 1],
            ['operation_date', '>=', $initDate],
            ['operation_date', '<=', $finalDate]
          ])
          ->sum('value');

        $saidas = TbLaunch::query()
          ->whereHas('closing', function ($q) use ($request, $def) {
            $q->where([
              ['status', 'like',  $request->query('closing_status', $def)],
              ['id', 'like',  $request->query('reference_month', $def)]
            ]);
          })
          ->where([
            ['idtb_type_launch', 'LIKE', $request->query('launch', $def)],
            ['status', 'LIKE', $request->query('status', $def)],
            ['idtb_caixa', 'LIKE', $request->query('caixa', $def)],
            ['idtb_operation', 'LIKE', 2],
            ['operation_date', '>=', $initDate],
            ['operation_date', '<=', $finalDate]
          ])
          ->sum('value');


        return floatval($entradas - $saidas);

      } elseif ($request->isMethod('post')) {
        $entradas = TbLaunch::query()
          ->whereHas('closing', function ($q) use ($request, $def) {
            $q->where([
              ['status', 'like',  $request->has('closing_status') ? $request['closing_status'] : $def],
              ['id', 'like',  $request->has('reference_month') ? $request['reference_month'] : $def]
            ]);
          })
          ->where([
            ['idtb_type_launch', 'LIKE', $request->has('launch') ? $request['launch'] : $def],
            ['status', 'LIKE', $request->has('status') ? $request['status'] : $def],
            ['idtb_caixa', 'LIKE', $request->has('caixa') ? $request['caixa'] : $def],
            ['idtb_operation', 'LIKE', 1],
            ['operation_date', '>=', $initDate],
            ['operation_date', '<=', $finalDate]
          ])
          ->sum('value');
        $saidas = TbLaunch::query()
          ->whereHas('closing', function ($q) use ($request, $def) {
            $q->where([
              ['status', 'like',  $request->has('closing_status') ? $request['closing_status'] : $def],
              ['id', 'like',  $request->has('reference_month') ? $request['reference_month'] : $def]
            ]);
          })
          ->where([
            ['idtb_type_launch', 'LIKE', $request->has('launch') ? $request['launch'] : $def],
            ['status', 'LIKE', $request->has('status') ? $request['status'] : $def],
            ['idtb_caixa', 'LIKE', $request->has('caixa') ? $request['caixa'] : $def],
            ['idtb_operation', 'LIKE', 2],
            ['operation_date', '>=', $initDate],
            ['operation_date', '<=', $finalDate]
          ])
          ->sum('value');

        return floatval($entradas - $saidas);
      }
    }
  }
  //retorna o saldo agrupado por tipo de pagamento, tpOperacao por default 1-entrada
  function saldoTipoPagamento($request, $tpOperacao = 1)
  {

    $def = '%';

      if ($request->isMethod('get')) 
      {

        return TbLaunch::query()
        ->with('payment_type')
          ->whereHas('closing', function ($q) use ($request, $def) {
            $q->where([
              ['status', 'like',  $request->query('closing_status', $def)],
              ['id', 'like',  $request->query('reference_month', $def)]
            ]);
          })
          ->where([
            ['idtb_type_launch', 'LIKE', $request->query('launch', $def)],
            ['status', 'LIKE', $request->query('status', $def)],
            ['idtb_caixa', 'LIKE', $request->query('caixa', $def)],
            ['idtb_operation', 'LIKE', $tpOperacao],
          ])
          ->select('idtb_payment_type', DB::raw('SUM(value) as total'))
          ->groupBy('idtb_payment_type')
          ->get();


      } 
      elseif ($request->isMethod('post')) 
      {

        return  TbLaunch::query()
        ->with('payment_type')
          ->whereHas('closing', function ($q) use ($request, $def) {
            $q->where([
              ['status', 'like',  $request->has('closing_status') ? $request['closing_status'] : $def],
              ['id', 'like',  $request->has('reference_month') ? $request['reference_month'] : $def]
            ]);
          })
          ->where([
            ['idtb_type_launch', 'LIKE', $request->input('launch', $def)],
            ['status', 'LIKE', $request->input('status', $def)],
            ['idtb_caixa', 'LIKE', $request->input('caixa', $def)],
            ['idtb_operation', 'LIKE', $tpOperacao],
          ])
          ->select('idtb_payment_type', DB::raw('SUM(value) as total'))
          ->groupBy('idtb_payment_type')
          ->get();

      }

  }

  public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
