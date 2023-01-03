<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\Datatables\Datatables;
use DB;


class LogsController extends Controller
{

   
        public function index(){ 

            return view('reports.logs');
    
        }
    
        public function query_DataTables(){
    
            $activies =  Datatables::of(DB::table('activity_log')
                                ->join('tb_cad_user', 'causer_id', '=', 'tb_cad_user.id')
                                ->select('activity_log.*', 'tb_cad_user.name'))
                                ->blacklist(['action'])
                                ->make(true);
           
            return $activies;
        }
}
