<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\TbTypeLaunch;
use App\Entities\TbClosing;
use App\Services\TbLaunchService;
use PDF;

class PdfController extends Controller
{

    protected $serviceLaunch;

    public function __construct(TbLaunchService $serviceLaunch)
    {
        
        $this->serviceLaunch  = $serviceLaunch;
    }

    //coletar dados para gerar  pdf de fechamentos
    public function closing_pdf(Request $request)
    {
        
        $request->request->add(['closing_status' => '%']);
        $tpCaixa    = TbTypeLaunch::where('id',$request['caixa'])->get('name')->toArray();
        $period     = TbClosing::where('id', $request['reference_month'])->get();

        $dados      = $this->serviceLaunch->find_Parameters($request);

        $request->request->add(['operation' => '1']);
        $entries    = $this->serviceLaunch->sum($request);

        $request->request->add(['operation' => '2']);
        $exits      = $this->serviceLaunch->sum($request);

        $balance    = $entries - $exits;

        $pdf = PDF::loadView('reports.closingPDF', compact('dados', 'period', 'entries', 'exits', 'balance', 'tpCaixa'));

        return $pdf->setPaper('a4')->stream('fechamento');

        
    }

}
