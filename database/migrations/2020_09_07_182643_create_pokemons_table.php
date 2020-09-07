<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePokemonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pokemons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('number');
            $table->string('type_1');
            $table->string('type_2')->nullable();
            $table->unsignedInteger('total_points');
            $table->unsignedInteger('health_points');
            $table->unsignedInteger('attack_points');
            $table->unsignedInteger('defense_points');
            $table->unsignedInteger('special_attack_points');
            $table->unsignedInteger('special_defense_points');
            $table->unsignedInteger('speed_points');
            $table->unsignedInteger('generation');
            $table->boolean('legendary');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pokemons');
    }
}
