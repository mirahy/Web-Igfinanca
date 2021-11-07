<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use DB;


class LogsController extends Controller
{

    public function index()
    {
        $activies = DB::table('activity_log')->paginate(10);
        
        $Length = $activies->count();
        for ($i = 0; $i < $Length; $i++) {  
            $name = DB::table('tb_cad_user')
                ->where('id', $activies[$i]->causer_id)
                ->get('name')->toArray();
            $activies[$i]->causer_id = $name[0]->name;
        }

        return view('reports.logs', compact('activies'));
    }
}
