<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pokemon;

class PokemonController extends Controller
{
    private $validationMessages = [
        'in' => 'The :attribute must be one of the following types: :values',
    ];

    public function index()
    {
        return Pokemon::orderBy('number')->paginate(20);
    }

    public function read($id)
    {
        $pokemon = Pokemon::find($id);
        if ($pokemon === null) {
            return response('Pokemon does not exist', 400);
        }

        return response()
            ->json($pokemon, 200);
    }

    public function create(Request $request)
    {
        if (!auth()->check()) {
            return response('Unauthorised', 401);
        }
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

            return response()->json($errors, 400);
        }
        $pokemon = $request->all();
        $pokemon['name'] = trim(strip_tags($pokemon['name']));
        $pokemon = Pokemon::create($pokemon);

        return response()
            ->json(['id' => $pokemon->id], 201);
    }

    public function delete($id)
    {
        if (!auth()) {
            return response('Unauthorised', 401);
        }
        $pokemon = Pokemon::find($id);
        if ($pokemon === null) {
            return response('Pokemon does not exist', 400);
        }
        $pokemon->delete();
    }

    public function update(Request $request, $id)
    {
        if (!auth()) {
            return response('Unauthorised', 401);
        }
        $pokemon = Pokemon::find($id);
        if ($pokemon === null) {
            return response('Pokemon does not exist', 400);
        }
        $types = implode(',', config('pokemon.types'));
        $validator = Validator::make($request->all(), [
            'name' => 'unique:pokemons|max:255',
            'number' => 'integer',
            'type_1' => "max:255|in:$types",
            'type_2' => "max:255|in:$types",
            'total_points' => 'integer',
            'health_points' => 'integer',
            'attack_points' => 'integer',
            'defense_points' => 'integer',
            'special_attack_points' => 'integer',
            'special_defense_points' => 'integer',
            'speed_points' => 'integer',
            'generation' => 'integer',
            'legendary' => 'boolean',
        ], $this->validationMessages);
        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json($errors, 400);
        }

        foreach ($request->all() as $attribute => $value) {
            $pokemon->{$attribute} = trim(strip_tags($value));
        }
        $pokemon->save();

        return response()->json($pokemon, 200);
    }
}
