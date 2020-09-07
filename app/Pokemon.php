<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    /**
    * The corresponding table name in database
    * @var string
    */
    protected $table = 'pokemons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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
        'legendary'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'number' => 'integer',
        'total_points' => 'integer',
        'health_points' => 'integer',
        'attack_points' => 'integer',
        'defense_points' => 'integer',
        'special_attack_points' => 'integer',
        'special_defense_points' => 'integer',
        'speed_points' => 'integer',
        'generation' => 'integer',
        'legendary' => 'boolean'
    ];
}
