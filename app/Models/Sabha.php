<?php

namespace App\Models;

use App\Models\Member;
use Illuminate\Cache\MemcachedLock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sabha extends Model
{
    use HasFactory;

    protected $table = 'sabhas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pradesh_id',
        'zone_id',
        'country_id',
        'state_id',
        'city_id',
        'name',
        'sabha_code',
        'flat_no',
        'building_name',
        'landmark',
        'street_name',
        'postcode',
        'latitude',
        'longitude',
        'sabha_type',
        'occurance',
        'sabha_day',
        'sabha_time',
        'status',
        'sabha_head_id',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function members_data()
    {
        return $this->hasOne(Member::class, 'sabha_id', 'id');
    }
}
