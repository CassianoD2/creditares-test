<?php

namespace App\Http\Controllers;

use App\Models\CulturaPrecos;
use App\Models\Culturas;
use App\Models\Unidades;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Visualizacao extends Controller
{
    public function showCulturas(Request $request)
    {
        $culturas = Culturas::all();

        return view('welcome', [
            'culturas' => $culturas
        ]);
    }

    public function visualizarPrecos(Culturas $id, Request $request)
    {

        $mes = $request->get('mes', Carbon::now()->month);
        $ano = $request->get('ano', Carbon::now()->year);
        $unidade = $request->get('unidade', 1);

        $unidades = Unidades::all();

        $precos = collect(CulturaPrecos::buscaPrecosPorCulturaMesAno($id->id, $mes, $ano, $unidade));

        $numberFormatter = new \NumberFormatter('pt_BR', \NumberFormatter::CURRENCY);

        /**
         * Poderia utilizar o Carbon, poderia, porém é necessário que um pacote de linguagem PT-BR estive instalado
         * para que o sistema pudesse traduzir os meses. Dependendo do sistema.
         */
        $arrayMeses = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
        ];

        $max = $numberFormatter->formatCurrency($precos->max('preco'), 'BRL');

        $min = $numberFormatter->formatCurrency($precos->min('preco'), 'BRL');

        $avg = $numberFormatter->formatCurrency($precos->avg('preco'), 'BRL');

        $precos = $precos->map(function ($item) use ($numberFormatter) {
            $item->data_preco = Carbon::createFromFormat('Y-m-d H:i:s', $item->data_preco)
                ->format('d/m/Y H:i:s');

            $item->preco = $numberFormatter->formatCurrency($item->preco, 'BRL');
            return $item;
        });
        return view('precos', [
            'cultura' => $id,
            'unidades' => $unidades,
            'precos' => $precos,
            'min' => $min,
            'max' => $max,
            'avg' => $avg,
            'meses' => $arrayMeses,
            'mesSelecionado' => $mes,
            'anoSelecionado' => $ano,
            'unidadeSelecionada' => $unidade
        ]);
    }
}
