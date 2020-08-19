<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\TbLaunch;
use PDF;

class PdfController extends Controller
{

    public function closing_pdf(Request $request)
    {

        $def = '%';

        $dados = (TbLaunch::query()
            ->with('user')
            ->with('caixa')
            ->with('type_launch')
            ->where([['idtb_type_launch', 'LIKE', $request->query('launch', $def)], ['status', 'LIKE', $request->query('status', $def)]]))->get();

        $data = [0];
        $year = 2020;
        $tot = TbLaunch::where([['idtb_type_launch', 'LIKE', $request->query('launch', $def)], ['status', 'LIKE', $request->query('status', $def)]])->sum('value');

        $pdf = PDF::loadView('reports.closingPDF', compact('dados', 'data', 'year', 'tot'));

        return $pdf->setPaper('a4')->stream('fechamento');
    }


}
