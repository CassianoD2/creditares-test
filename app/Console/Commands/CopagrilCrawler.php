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
        $retorno = $this->copagrilService->getHtml(2023, 1);

        dd($retorno);
        $domHtml = new \DOMDocument();
        $domHtml->loadHTML($retorno['body']);

        //Utilizo o DOMXPath para poder andar pelo HTML com ajuda do seletor do XPATH do XML.
        $htmlXpath = new \DOMXPath($domHtml);

        $headers = [];
        $data = [];

        $bodyData = $htmlXpath->evaluate('//table[contains(@class, "tabela-precos")]//tr');
        $count = 0;
        foreach ($bodyData as $datum) {
            //Faço o carregamento do header dentro de uma variável para poder fazer um parse posteriormente.
            if ($count == 0 && $datum->childNodes->length == 4) {
                foreach ($datum->childNodes as $childNode) {
                    $headers[] = $childNode->nodeValue;
                }
                $count++;
                continue;
            }

            //Faço o insert dos dados do tbody da tabela para processamento posterior.
            if ($datum->childNodes->length == 4) {
                foreach ($datum->childNodes as $childNode) {
                    $data[$count][] = $childNode->nodeValue;
                }
                $count++;
            }
        }

        $data = array_map(function ($item) use ($headers) {
            return [
                $headers[0] => implode(' ', explode(' - ', $item[0])),
                $headers[1] => $item[1],
                $headers[2] => $item[2],
                $headers[3] => $item[3],
            ];
        }, $data);

        dd($data);
        return 0;
    }
}
