<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Pokemon;

class PokemonController extends Controller
{
    private $validationMessages = [
        'in' => 'The :attribute must be one of the following types: :values',
    ];

    public function index()
    {
        return Pokemon::orderBy('number')->paginate(20);
    }

    public function create(Request $request)
    {
        $types = implode(',', config('pokemon.types'));
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:pokemons|max:255',
            'number' => 'required|integer',
            'type_1' => "required|max:255|in:$types",
            'type_2' => "required|max:255|in:$types",
            'total_points' => 'required|integer',
            'health_points' => 'required|integer',
            'attack_points' => 'required|integer',
            'defense_points' => 'required|integer',
            'special_attack_points' => 'required|integer',
            'special_defense_points' => 'required|integer',
            'speed_points' => 'required|integer',
            'generation' => 'required|integer',
            'legendary' => 'required|boolean',
        ], $this->validationMessages);
        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()
                ->json($errors, 400);
        }
        $pokemon = Pokemon::create($request->all());

        return response()
            ->json(['id' => $pokemon->id], 201);
    }

    public function delete($id)
    {
        $pokemon = Pokemon::find($id);
        if ($pokemon === null) {
            return response('Pokemon does not exist', 400);
        }
        $pokemon->delete();
    }
}
