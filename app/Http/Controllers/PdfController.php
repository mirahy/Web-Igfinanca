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

        $this->middleware('role:Admin|Edit|launche-approver|launch-manager', ['only' => ['closing_pdf']]);
    }

    //coletar dados para gerar  pdf de fechamentos
    public function closing_pdf(Request $request)
    {
        
        
        $request->request->add(['status' => 1 ]);
        $tpCaixa    = TbTypeLaunch::where('id',$request['caixa'])->get('name')->toArray();
        $period     = TbClosing::where('id', $request['reference_month'])->get();
        $numMes     = $this->serviceLaunch->number_month($period[0]['month'] );
        $nomeArq    = $numMes.' '.$tpCaixa[0]['name'] . ' - ' . $period[0]['month']. '_' .$period[0]['year'];

        $dados      = $this->serviceLaunch->find_Parameters($request);
        
        $request->request->add(['operation' => '1']);
        $entries    = $this->serviceLaunch->sum($request);

        $request->request->add(['operation' => '2']);
        $exits      = $this->serviceLaunch->sum($request);
        
        $request->request->remove('operation');
        $request->request->add(['closing_status' => 0]);
        $startBalance = $request['caixa'] == 2 ? $this->serviceLaunch->saldo($request) : 0;
        $entries = $entries + $startBalance;

        $balance    = $entries - $exits;

        // return View('reports.closingPDF', compact('dados', 'period', 'entries', 'exits', 'balance', 'tpCaixa'));
        $pdf = PDF::loadView('reports.closingPDF', compact('dados', 'period', 'entries', 'exits', 'balance', 'tpCaixa'));

        return $pdf->setPaper('a4')->stream($nomeArq.'.pdf');

        
    }

}
