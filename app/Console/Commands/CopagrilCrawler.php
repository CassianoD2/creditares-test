<?php

namespace App\Console\Commands;

use App\Http\Library\TableParse;
use App\Http\Service\Copagril;
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
        $retorno = $this->copagrilService->getHtml(2022, 1);



        dd($data);
        return 0;
    }
}
