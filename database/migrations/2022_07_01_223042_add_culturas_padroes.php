<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCulturasPadroes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('culturas')->insert([
            [
                'nome' => 'Soja',
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'nome' => 'Milho',
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'nome' => 'Trigo',
                'created_at' => \Carbon\Carbon::now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete("DELETE FROM culturas WHERE nome IN ('Soja', 'Milho', 'Trigo')");
    }
}
