<?php
namespace App\Http\Service;

use GuzzleHttp\Client;

class Copagril
{
    /** @var string URL da Copagril na qual é disponibilizada a tabela de preços. */
    protected $url = "https://www.copagril.com.br/precos";

    public function getHtml($ano = null, $mes = null, $cidade = 'unidade_parana')
    {
        $guzzle = new Client();

        $request = $guzzle->request('GET', $this->url ."/{$ano}/{$mes}", [
            'headers'=> [
                'Accept-Encoding'=> 'gzip, deflate, br',
                'Cookie'=> "cidade_precos={$cidade}",
            ]
        ]);

        /**
         * Auxilia a identificar qual o tipo de retorno, retorno ou falha.
         */
        if ($request->getStatusCode() >= 200 && $request->getStatusCode() < 300) {
            $html = $request->getBody()->getContents();

            return [
                'error' => false,
                'status' => $request->getStatusCode(),
                'body' => $html,
            ];

        } else {
            return [
                'error' => true,
                'status' => $request->getStatusCode(),
                'body' => 'Consulta indisponível!',
            ];
        }

    }
}
