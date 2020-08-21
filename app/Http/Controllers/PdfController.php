<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\TbLaunch;
use App\Services\TbLaunchService;
use PDF;

class PdfController extends Controller
{

    protected $serviceLaunch;

    public function __construct(TbLaunchService $serviceLaunch)
    {
        
        $this->serviceLaunch  = $serviceLaunch;
    }

    //gerar pdf de fechamentos
    public function closing_pdf(Request $request)
    {
        
        $def = '%';
        $tpCaixa    = 'Dízimos';
        $month      = 'Agosto';
        $year       = '2020';

        $dados      = $this->serviceLaunch->find_Parameters($request);

        $request->request->add(['operation' => '1']);
        $entries    = $this->serviceLaunch->sum($request);

        $request->request->add(['operation' => '2']);
        $exits      = $this->serviceLaunch->sum($request);

        $balance    = $entries - $exits;

        $pdf = PDF::loadView('reports.closingPDF', compact('dados', 'month', 'year', 'entries', 'exits', 'balance', 'tpCaixa'));

        return $pdf->setPaper('a4')->stream('fechamento');
    }


}
