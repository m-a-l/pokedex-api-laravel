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
    // test if create returns 201
    // test if create returns error on name already exists
    // test if create returns validation errors

    // test if delete returns error on id does not exists
    // test if delete returns 204
    
    // # same for update (200)
}
