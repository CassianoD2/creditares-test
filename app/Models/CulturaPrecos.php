<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CulturaPrecos extends Model
{
    use HasFactory;

    public static function buscaPrecosPorCulturaMesAno($id, $mes, $ano, $unidade)
    {
        return DB::select("SELECT
                                    preco,
                                    data_preco,
                                    date_part('year', data_preco) as ano,
                                    date_part('month', data_preco) as mes
                                 FROM cultura_precos
                                 WHERE cultura_id = :cultura
                                 and unidade_id = :unidade
                                 and EXTRACT(MONTH FROM data_preco) = :mes
                                 and EXTRACT(YEAR FROM data_preco) = :ano
                                 order by data_preco asc",
            [
                'cultura' => $id,
                'unidade' => $unidade,
                'mes' => $mes,
                'ano' => $ano,
            ]);
    }

    public function insertBatch($arrays)
    {
        return DB::table('cultura_precos')->insertOrIgnore($arrays);
    }
}
