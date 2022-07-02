<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Unidades extends Model
{
    use HasFactory;

    public static function getUnidadesByCookieName($unidade)
    {
        return DB::selectOne("SELECT * FROM unidades WHERE cookie_name = :unidadeNome", ['unidadeNome' => $unidade]);
    }
}
