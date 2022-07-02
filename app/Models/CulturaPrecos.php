<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CulturaPrecos extends Model
{
    use HasFactory;

    public function insertBatch($arrays)
    {
        return DB::table('cultura_precos')->insertOrIgnore($arrays);
    }
}
