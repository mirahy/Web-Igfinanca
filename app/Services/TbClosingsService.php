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


}