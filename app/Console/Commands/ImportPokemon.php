<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CsvService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use App\Pokemon;
use  InvalidArgumentException;
use Storage;

class ImportPokemon extends Command
{
    public $headersToAttributes = [
        '#' => 'number',
        'Name' => 'name',
        'Type 1' => 'type_1',
        'Type 2' => 'type_2',
        'Total' => 'total_points',
        'HP' => 'health_points',
        'Attack' => 'attack_points',
        'Defense' => 'defense_points',
        'Sp. Atk' => 'special_attack_points',
        'Sp. Def' => 'special_defense_points',
        'Speed' => 'speed_points',
        'Generation' => 'generation',
        'Legendary' => 'legendary'
    ];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pokemons:import {filename=pokemon}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drops all data present in the pokemons table and populate it via the csv storage/app/csv/{filename}.csv, default pokemon.csv';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Verifications
        $fileName = explode('.', $this->argument('filename'))[0]; // ignore .csv
        if (!$this->confirm('/!\ WILL DELETE ALL POKEMON DATA /!\ Do you really wish to run this command?')) {
            return 1;
        }
        if (!Storage::exists("/csv/$fileName.csv")) {
            throw new FileNotFoundException(
                "storage/app/csv/pokemon.csv does not exist"
            );
        }
        $csvService = new CsvService();
        $headers = $csvService->getHeaders($fileName);
        //dd(array_keys($this->headersToAttributes));
        if ($headers != array_keys($this->headersToAttributes)) {
            throw new InvalidArgumentException(
                "Headers do not match headersToAttributes definition"
            );
        }
        // Actual import
        \DB::table('pokemons')->truncate();
        $pokemonsCsv = $csvService->read($fileName);
        $pokemons = $this->convertToPokemons($pokemonsCsv);
        foreach ($pokemons as $pokemon) {
            $pokemon->save();
        }
    }

    /**
    * Converts the outputs of the array to a nice Pokemon collection
    * @param array $pokemonsCsv the output of the csv
    * @return Illuminate\Support\Collection;
    */
    public function convertToPokemons(array $pokemonsCsv)
    {
        $pokemons = collect();
        foreach ($pokemonsCsv as $pokemon) {
            $attributes = [];
            foreach ($pokemon as $header => $value) {
                if ($value == 'False' || $value == 'false' || $value == 'True' || $value == 'true') {
                    $value = ($value == 'False' || $value == 'false' ? 0 : 1);
                }
                $attributes[$this->headersToAttributes[$header]] = $value;
            }
            $pokemons->add(new Pokemon($attributes));
        }

        return $pokemons;
    }
}
