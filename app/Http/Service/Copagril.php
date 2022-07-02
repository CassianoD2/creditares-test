<?php

namespace App\Http\Service;

use App\Models\CulturaPrecos;
use App\Models\Culturas;
use App\Models\Unidades;
use Carbon\Carbon;
use GuzzleHttp\Client;

class Copagril
{
    /** @var string URL da Copagril na qual é disponibilizada a tabela de preços. */
    protected $url = "https://www.copagril.com.br/precos";

    public function getHtml($ano = null, $mes = null, $cidade = 'unidade_parana')
    {
        $guzzle = new Client();

        $request = $guzzle->request('GET', $this->url . "/{$ano}/{$mes}", [
            'headers' => [
                'Accept-Encoding' => 'gzip, deflate, br',
                'Cookie' => "cidade_precos={$cidade}",
            ]
        ]);

        $return = [
            'error' => false,
            'status' => $request->getStatusCode(),
            'body' => null,
            'cidade' => $cidade
        ];
        /**
         * Auxilia a identificar qual o tipo de retorno, retorno ou falha.
         */
        if ($return['status'] >= 200 && $return['status'] < 300) {
            $html = $request->getBody()->getContents();

            $return['error'] = false;
            $return['body'] = $html;
        }

        return $return;
    }

    /**
     * Responsável por fazer a leitura dos dados retornados do getHTML.
     *
     * @param $retorno Retorno do getHTML do serviço.
     * @return array
     */
    public function processaTabela($retorno)
    {
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

        return [
            'cidade' => $retorno['cidade'],
            'data' => $data,
        ];
    }

    /**
     * Função responsável por fazer a inserção no banco de dados.
     *
     * @param $data
     *
     * @return void
     */
    public function populaTabelas($data)
    {
        $idUnidade = Unidades::getUnidadesByCookieName($data['cidade']);
        $culturas = Culturas::all();

        $arrayInsert = [];
        //Percorro os registro para poder realizar a inserção;
        foreach ($data['data'] as $registro) {
            $arrayInicial = [
                'unidade_id' => $idUnidade->id,
                'cultura_id' => null,
                'preco' => null,
                'data_preco' => null,
            ];

            foreach ($registro as $key => $item) {
                switch ($key) {
                    case 'Data':
                        $arrayInicial['data_preco'] = Carbon::createFromFormat('d/m/Y H:i', $item)
                            ->format('Y-m-d H:i:s');
                        break;

                    case 'Soja':
                        $arrayInicial['cultura_id'] = $culturas->where('nome', '=', 'Soja')->pluck('id')->first();
                        $arrayInicial['preco'] = (float)str_replace(['R$', ',', ' '], ['', '.', ''], $item);
                        $arrayInsert[] = $arrayInicial;
                        break;

                    case 'Milho':
                        $arrayInicial['cultura_id'] = $culturas->where('nome', '=', 'Milho')->pluck('id')->first();
                        $arrayInicial['preco'] = (float)str_replace(['R$', ',', ' '], ['', '.', ''], $item);
                        $arrayInsert[] = $arrayInicial;
                        break;

                    case 'Trigo':
                        $arrayInicial['cultura_id'] = $culturas->where('nome', '=', 'Trigo')->pluck('id')->first();
                        $arrayInicial['preco'] = (float)str_replace(['R$', ',', ' '], ['', '.', ''], $item);
                        $arrayInsert[] = $arrayInicial;
                        break;
                }
            }

            if (CulturaPrecos::insertBatch($arrayInsert)) {
                $arrayInsert = [];
            }
        }
    }
}
