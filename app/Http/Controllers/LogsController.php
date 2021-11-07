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
        return view('reports.logs',compact('activies'));
    }
}
