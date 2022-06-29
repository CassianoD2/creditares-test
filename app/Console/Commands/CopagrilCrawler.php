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

        $domHtml = new \DOMDocument();
        $domHtml->loadHTML($retorno['body']);

        //Utilizo o DOMXPath para poder andar pelo HTML com ajuda do seletor do XPATH do XML.
        $htmlXpath = new \DOMXPath($domHtml);

        $headers = [];
        $data = [];

        /**
         * Utilizando o modelo de query do XPATH do XML eu consigo buscar uma tabela dentro do ROOT ou seja do HTML.
         * Buscando a tabela eu pego todas as linhas dela, como a leitura é sempre de cima pra baixa e da esquerda pra
         * direita, o HEADER sempre será o primeiro TR.
         */
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

        /**
         * Faço uma mapa através do array Data para que eu possa utilizar os dados e inserir as chaves de acordo com o
         * header assim tornando mais legivel o meu array através de um dump ou até mesmo para jogar para tela.
         */
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
