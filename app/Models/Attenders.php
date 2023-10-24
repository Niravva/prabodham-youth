<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attenders extends Model
{
    use HasFactory;

    protected $table = 'attenders';

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
        'attendance_id',
        'member_id',
        'present',
        'attendance_by',
        'created_at',
        'updated_at',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendances::class, 'attendance_id');
    }
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
