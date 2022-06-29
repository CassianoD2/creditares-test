<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCulturaPrecosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cultura_precos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cultura_id');
            $table->decimal('preco', 12,2);
            $table->dateTime('data_preco')->unique();

            $table->foreign('cultura_id', 'cultura_id')->references('id')->on('culturas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cultura_precos');
    }
}
