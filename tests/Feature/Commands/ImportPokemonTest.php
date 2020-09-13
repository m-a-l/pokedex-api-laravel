<?php

namespace Tests\Feature;

use Tests\TestCase;
use  Illuminate\Contracts\Filesystem\FileNotFoundException;
use App\Console\Commands\ImportPokemon as ImportCommand;
use Illuminate\Support\Collection;
use App\Models\Pokemon;
use App\Services\CsvService;
use  InvalidArgumentException;
use Schema;

class ImportPokemonTest extends TestCase
{
    /**
     * asks confirmation to execute
     * @test
     * @return void
     */
    public function asksConfirmationToExecute()
    {
        $this->artisan('pokemons:import')
            ->expectsConfirmation('/!\ WILL DELETE ALL POKEMON DATA /!\ Do you really wish to run this command?', 'no')
            ->assertExitCode(1);
    }
    /**
     * throws error if csv does not exist
     * @test
     * @return void
     */
    public function throwsErrorIfCsvDoesNotExist()
    {
        $this->expectException(FileNotFoundException::class);
        $this->artisan('pokemons:import yolololo')
            ->expectsQuestion('/!\ WILL DELETE ALL POKEMON DATA /!\ Do you really wish to run this command?', 'yes');
    }

    /**
     * throws error if csv does not exist
     * @test
     * @return void
     */
    public function headersToAttributesReallyContainsPokemonAttributes()
    {
        $command = new ImportCommand();
        $columns = Schema::getColumnListing('pokemons');
        unset($columns[0], $columns[14], $columns[15]); // remove id, created_at, updated_at
        $columns = array_values($columns);
        sort($columns);
        $headersToAttributes = array_values($command->headersToAttributes);
        sort($headersToAttributes);
        $this->assertEquals($headersToAttributes, $columns);
    }

    /**
     * throws error if csv headers do not match headers to attributes
     * @test
     * @return void
     */
    public function throwsErrorIfCsvHeadersDoNotMatchHeadersToAttributes()
    {
        $this->expectException(InvalidArgumentException::class);
        $csvService = new CsvService();
        $pokemons = [
            [
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
            ]
        ];
        $csvService->create('pokemon-test', [ 'name', 'number', 'type_1', 'type_2', 'total_points', 'health_points', 'attack_points', 'defense_points', 'special_attack_points', 'special_defense_points', 'speed_points', 'generation', 'legendary'], $pokemons);
        $this->artisan('pokemons:import pokemon-test')
            ->expectsQuestion('/!\ WILL DELETE ALL POKEMON DATA /!\ Do you really wish to run this command?', 'yes');
        unlink(storage_path() . '/app/csv/pokemon-test.csv');
    }

    /**
     * convert to pokemon returns pokemon collection
     * @test
     * @return void
     */
    public function convertToPokemonReturnsPokemonCollection()
    {
        $command = new ImportCommand();
        $csvService = new CsvService();
        $content = [
            [
                'Name' => 'Bulbasaur',
                '#' => 1,
                'Type 1' => 'Grass',
                'Type 2' => 'Poison',
                'Total' => 318,
                'HP' => 45,
                'Attack' => 49,
                'Defense' => 49,
                'Sp. Atk' => 65,
                'Sp. Def' => 65,
                'Speed' => 45,
                'Generation' => 1,
                'Legendary' => false
            ]
        ];
        $csvService->create('pokemon-test', ['#' ,'Name' ,'Type 1' ,'Type 2' ,'Total' ,'HP' ,'Attack' ,'Defense','Sp. Atk' ,'Sp. Def' ,'Speed','Generation' ,'Legendary'], $content);
        $pokemonsCsv = $csvService->read('pokemon-test');
        $pokemons = $command->convertToPokemons($pokemonsCsv);
        unlink(storage_path() . '/app/csv/pokemon-test.csv');

        $this->assertInstanceOf(Collection::class, $pokemons);
        $this->assertInstanceOf(Pokemon::class, $pokemons->first());
    }

    /**
    * convert to pokemon returns appropriate types
    * @test
    * @return void
    */
    public function convertToPokemonReturnsAppropriateTypes()
    {
        $command = new ImportCommand();
        $csvService = new CsvService();
        $content = [
            [
                'Name' => 'Bulbasaur',
                '#' => 1,
                'Type 1' => 'Grass',
                'Type 2' => 'Poison',
                'Total' => 318,
                'HP' => 45,
                'Attack' => 49,
                'Defense' => 49,
                'Sp. Atk' => 65,
                'Sp. Def' => 65,
                'Speed' => 45,
                'Generation' => 1,
                'Legendary' => false
            ]
        ];
        $csvService->create('pokemon-test', ['#' ,'Name' ,'Type 1' ,'Type 2' ,'Total' ,'HP' ,'Attack' ,'Defense','Sp. Atk' ,'Sp. Def' ,'Speed','Generation' ,'Legendary'], $content);
        $pokemonsCsv = $csvService->read('pokemon-test');
        $pokemons = $command->convertToPokemons($pokemonsCsv);
        unlink(storage_path() . '/app/csv/pokemon-test.csv');
        $this->assertEquals($pokemons->first()->toArray(), [
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
}
