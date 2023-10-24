<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendances extends Model
{
    use HasFactory;

    protected $table = 'attendances';

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
        'sabha_date',
        'vakta1',
        'vakta1_topic',
        'vakta2',
        'vakta2_topic',
        'status',
        'reason',
        'created_at',
        'updated_at',
    ];

    public function attenders_data()
    {
        return $this->hasOne(Attenders::class, 'attendance_id', 'id');
    }
}
