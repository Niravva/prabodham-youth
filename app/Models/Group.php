<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'state_id',
        'city_id',
        'pradesh_id',
        'zone_id',
        'sabha_id',
        'name',
        'status',
        'created_by',
        'created_at',
        'updated_at',
    ];
}
