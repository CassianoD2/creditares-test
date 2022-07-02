<?php

namespace App\Console\Commands;

use App\Http\Library\TableParse;
use App\Http\Service\Copagril;
use App\Models\Unidades;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CopagrilCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copagril:crawler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawler para obtenção dos valores disponíveis na URL da Copagril Preços';

    /** @var Copagril */
    protected $copagrilService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->copagrilService = new Copagril();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $unidades = Unidades::all();

        $anoInicial = 2010;
        $mesInicial = 1;

        $anoAtual = Carbon::now()->year;
        $mesFinal = Carbon::now()->month;
//
//        $retorno = $this->copagrilService->getHtml(2022, 07, "unidades_parana");
//        $data = $this->copagrilService->processaTabela($retorno);
//        $this->copagrilService->populaTabelas($data);
//
//        dd(1);
        try {
            foreach ($unidades as $unidade) {
                for ($ano = $anoInicial; $ano <= $anoAtual; $ano++) {
                    if ($ano != $anoAtual) {
                        $mesFinal = 12;
                    }

                    for ($mes = $mesInicial; $mes <= $mesFinal; $mes++) {
                        dump("Processando mês: {$mes} do ano: {$ano}");
                        $retorno = $this->copagrilService->getHtml($ano, $mes, $unidade->cookie_name);
                        $data = $this->copagrilService->processaTabela($retorno);
                        $this->copagrilService->populaTabelas($data);
                    }
                }
            }
        } catch (\Exception $e) {
            dump($mes, $ano);
            dump("Erro: {$e->getMessage()} | Arquivo: {$e->getFile()} | Linha: {$e->getLine()}");
        }


        return 0;
    }
}
