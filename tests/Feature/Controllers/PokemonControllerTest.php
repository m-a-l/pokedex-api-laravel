<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Pokemon;

class PokemonControllerTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function listReturnsJson()
    {
        $response = $this->get('/api/pokemons');

        $response
            ->assertStatus(200)
            ->assertJson([
                'per_page' => 20,
            ]);
    }

    /**
     * create throws error if type is not valid
     * @test
     * @return void
     */
    public function createThrowsErrorIfTypeIsNotValid()
    {
        $response = $this->postJson('/api/pokemons/new', [
            'name' => 'Bulbasaur 2',
            'number' => 1,
            'type_1' => 'Grass',
            'type_2' => 'Poisonous',
            'total_points' => 318,
            'health_points' => 45,
            'attack_points' => 49,
            'defense_points' => 49,
            'special_attack_points' => 65,
            'special_defense_points' => 65,
            'speed_points' => 45,
            'generation' => 1,
            'legendary' => false
        ]);
        $response
            ->assertStatus(400)
            ->assertJson([
                "type_2" => [
                    "The type 2 must be one of the following types: Bug, Dark, Dragon, Electric, Fairy, Fighting, Fire, Flying, Ghost, Grass, Ground, Ice, Normal, Poison, Psychic, Rock, Steel, Water"
                ]
            ]);
    }

    /**
     * create throws error if name exists
     * @test
     * @return void
     */
    public function createThrowsErrorIfNameExists()
    {
        $response = $this->postJson('/api/pokemons/new', [
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
        ]);
        $response
            ->assertStatus(400)
            ->assertJson([
                "name" => [
                    "The name has already been taken."
                ]
            ]);
    }

    /**
     * create throws error if fields are missing
     * @test
     * @return void
     */
    public function createThrowsErrorsIfFieldAreMissing()
    {
        $response = $this->postJson('/api/pokemons/new', [
            'number' => 49,
        ]);
        $response
            ->assertStatus(400)
            ->assertJson([
                "name" => [
                    "The name field is required."
                ],
                "health_points" => [
                    "The health points field is required."
                ],
                "defense_points" => [
                    "The defense points field is required."
                ],
                "attack_points" => [
                    "The attack points field is required."
                ],
                "type_1" => [
                    "The type 1 field is required."
                ],
                "type_2" => [
                    "The type 2 field is required."
                ],
                "total_points" => [
                    "The total points field is required."
                ],
                "legendary" => [
                    "The legendary field is required."
                ],
                "generation" => [
                    "The generation field is required."
                ],
                "generation" => [
                    "The generation field is required."
                ],
                'speed_points' => [
                    "The speed points field is required."
                ],
                'special_defense_points' => [
                    "The special defense points field is required."
                ],
                'special_attack_points' => [
                    "The special attack points field is required."
                ],
            ]);
    }
     
    /**
     * create throws errors if types are not accurate
     * @test
     * @return void
     */
    public function createThrowsErrorsIfTypesAreNotAccurate()
    {
        $response = $this->postJson('/api/pokemons/new', [
            'name' => 'Bulbasaur',
            'number' => 1,
            'type_1' => 1,
            'type_2' => 'Poison',
            'total_points' => 'help',
            'health_points' => 'help',
            'attack_points' => 'help',
            'defense_points' => 'help',
            'special_attack_points' => 'help',
            'special_defense_points' => 'help',
            'speed_points' => 'help',
            'generation' => 'jwhfeiuwe',
            'legendary' => 'lol'
        ]);

        $response
            ->assertStatus(400)
            ->assertJson([
                'total_points' => [
                    'The total points must be an integer.'
                ],
                'health_points' => [
                    'The health points must be an integer.'
                ],
                'attack_points' => [
                    'The attack points must be an integer.'
                ],
                'defense_points' => [
                    'The defense points must be an integer.'
                ],
                'special_attack_points' => [
                    'The special attack points must be an integer.'
                ],
                'special_defense_points' => [
                    'The special defense points must be an integer.'
                ],
                'speed_points' => [
                    'The speed points must be an integer.'
                ],
                'generation' => [
                    'The generation must be an integer.'
                ],
                'type_1' => [
                    'The type 1 must be one of the following types: Bug, Dark, Dragon, Electric, Fairy, Fighting, Fire, Flying, Ghost, Grass, Ground, Ice, Normal, Poison, Psychic, Rock, Steel, Water'
                ]
            ]);
    }

    /**
     * create returns201
     * @test
     * @return void
     */
    public function createReturns201AndAddsAPokemon()
    {
        $countBefore = Pokemon::count();
        $response = $this->postJson('/api/pokemons/new', [
            'name' => '<bold>Bulbasaur 2</bold>',
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
        ]);
        $response
            ->assertStatus(201);
        $this->assertEquals($countBefore + 1, Pokemon::count());
        $pokemon = json_decode($response->getContent());
        $pokemon = Pokemon::find($pokemon->id);
        $pokemon->delete();
    }

    /**
     * delete throws error if id does not exist
     * @test
     * @return void
     */
    public function deleteThrowsErrorIfIdDoesNotExist()
    {
        $response = $this->delete("/api/pokemon/132940347348")
            ->assertStatus(400);
    }

    /**
     * delete works
     * @test
     * @return void
     */
    public function deleteWorks()
    {
        $response = $this->postJson('/api/pokemons/new', [
            'name' => 'Bulbasaur 2',
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
        ]);
        $pokemon = json_decode($response->getContent());
        $countBefore = Pokemon::count();

        $response = $this->delete("/api/pokemon/$pokemon->id")
            ->assertStatus(200);
        $this->assertEquals($countBefore - 1, Pokemon::count());
    }

    /**
     * read returns good pokemon
     * @test
     * @return void
     */
    public function readReturnsGoodPokemon()
    {
        $response = $this->get('/api/pokemon/1');

        $response
            ->assertStatus(200)
            ->assertJson([
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
            ]);
    }

    /**
     * read throws errors if pokemon not found
     * @test
     * @return void
     */
    public function readThrowsErrorsIfPokemonNotFound()
    {
        $response = $this->get('/api/pokemon/2378493845384509');
        $response
            ->assertStatus(400);
    }

    /**
     * update throws error if pokemon not found
     * @test
     * @return void
     */
    public function updateThrowsErrorIfPokemonNotFound()
    {
        $response = $this->putJson('/api/pokemon/2374792884920394', [
            'name' => 'Bulbasaaaaaaur'
        ]);
        $response
            ->assertStatus(400);
    }

    /**
     * update throws error if pokemon not found
     * @test
     * @return void
     */
    public function updateWorksAndStripsTags()
    {
        $response = $this->putJson('/api/pokemon/1', [
            'name' => '<bold>Bulbasaaaaaaur</bold>'
        ]);
        $response
            ->assertStatus(200);
        $pokemon = Pokemon::find(1);

        $this->assertEquals($pokemon->name, 'Bulbasaaaaaaur');
        $pokemon->name = 'Bulbasaur';
        $pokemon->save();
    }
}
