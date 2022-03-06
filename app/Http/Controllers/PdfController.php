<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\TbTypeLaunch;
use App\Entities\TbClosing;
use App\Services\TbLaunchService;
use PDF;
use SnappyPDF;


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
        $base       = session()->get('name_base');

        $dados      = $this->serviceLaunch->find_Parameters($request);
        $dados2     = $this->serviceLaunch->saldoTipoPagamento($request);
        
        
        $request->request->add(['operation' => '1']);
        $entries    = $this->serviceLaunch->sum($request);

        $request->request->add(['operation' => '2']);
        $exits      = $this->serviceLaunch->sum($request);
        
        $request->request->remove('operation');
        $request->request->add(['closing_status' => 0]);
        $startBalance   = $request['caixa'] == 2 ? $this->serviceLaunch->saldo($request) : 0;
        $entries        = $entries + $startBalance;

        $balance        = $entries - $exits;


        //retorna a view
        // return View('reports.closingPDF', compact('dados', 'dados2', 'period', 'entries', 'exits', 'balance', 'tpCaixa', 'startBalance'));

        //DomPDF
        // $pdf = PDF::loadView('reports.closingPDF', compact('dados', 'dados2', 'period', 'entries', 'exits', 'balance', 'tpCaixa', 'startBalance'));
        // return $pdf->setPaper('a4')->stream($nomeArq.'.pdf');

        //SnappyPDF
        
        $pdf = SnappyPDF::loadView('reports.closingPDF', compact('dados', 'dados2', 'period', 'entries', 'exits', 'balance', 'tpCaixa', 'startBalance'))
                            ->setPaper('a4')->setOption('footer-right', 'Página [page] de [topage]')->setOption('footer-left', '[date] - [time]')
                            ->setOption('header-left', $base);
        return $pdf->inline($nomeArq.'.pdf');

        /* ->setOption(SnappyPDF)
        [page] Replaced by the number of the pages currently being printed
        [frompage] Replaced by the number of the first page to be printed
        [topage] Replaced by the number of the last page to be printed
        [webpage] Replaced by the URL of the page being printed
        [section] Replaced by the name of the current section
        [subsection] Replaced by the name of the current subsection
        [date] Replaced by the current date in system local format
        [isodate] Replaced by the current date in ISO 8601 extended format
        [time] Replaced by the current time in system local format
        [title] Replaced by the title of the of the current page object
        [doctitle] Replaced by the title of the output document
        [sitepage] Replaced by the number of the page in the current site being converted
        [sitepages] Replaced by the number of pages in the current site being converted */

        
    }

}
