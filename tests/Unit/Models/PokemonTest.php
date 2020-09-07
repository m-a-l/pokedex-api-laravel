<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use App\Pokemon;

class PokemonTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function pokemonsTableExists()
    {
        $this->assertTrue(Schema::hasTable('pokemons'));
    }

    /**
     * @test
     * @return void
     */
    public function pokemonsTableHasExpectedColumns()
    {
        $this->assertTrue(Schema::hasColumns('pokemons', [
            'id',
            'name',
            'number',
            'type_1',
            'type_2',
            'total_points',
            'health_points',
            'attack_points',
            'defense_points',
            'special_attack_points',
            'special_defense_points',
            'speed_points',
            'generation',
            'legendary',
            'created_at',
            'updated_at'
        ]), 1);
    }

    /**
     * @test
     * @return void
     */
    public function pokemonFieldsAreMassAssignable()
    {
        $fields = [
            'name' => 'Bulbasaur',
            'number' => 1,
            'type_1' => 'Grass',
            'type_2' => 'Poison',
            'total_points' => 318,
            'health_points' => 45,
            'attack_points' => 49,
            'defense_points' => 49,
            'special_attack_points' => 65,
            'special_defense_points' => 65,
            'speed_points' => 45,
            'generation' => 1,
            'legendary' => false
        ];
        $pokemon = new Pokemon($fields);
        $this->assertEquals($pokemon->getAttributes(), $fields);
    }
}
