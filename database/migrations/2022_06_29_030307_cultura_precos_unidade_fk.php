<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CulturaPrecosUnidadeFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cultura_precos', function (Blueprint $table) {
            $table->foreignId('unidade_id');
            $table->foreign('unidade_id', 'unidade_id')->references('id')->on('unidades');

            $table->unique(['cultura_id', 'data_preco', 'preco', 'unidade_id'], 'cultura_preco_data_preco_unidade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cultura_precos', function (Blueprint $table) {
            $table->dropForeign('unidade_id');
            $table->dropColumn('unidade_id');
        });
    }
}
