<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
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
}
