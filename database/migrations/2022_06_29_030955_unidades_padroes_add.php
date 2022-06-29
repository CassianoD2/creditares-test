<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UnidadesPadroesAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('unidades')->insert([
            [
                'nome' => 'Unidade Parana',
                'cookie_name' => 'unidades_parana'
            ],
            [
                'nome' => 'Unidade Mato Grosso do Sul',
                'cookie_name' => 'unidades_matogrossosul'
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete("DELETE FROM unidades WHERE cookie_name IN ('unidades_parana', 'unidades_matogrossosul')");
    }
}
