<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $table = 'zones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pradesh_id',
        'country_id',
        'state_id',
        'city_id',
        'name',
        'status',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function sabha_data()
    {
        return $this->hasOne(Sabha::class, 'zone_id', 'id');
    }
}
