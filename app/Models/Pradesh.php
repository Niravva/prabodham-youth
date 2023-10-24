<?php

namespace App\Models;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pradesh extends Model
{
    use HasFactory;

    protected $table = 'pradeshs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'state_id',
        'city_id',
        'name',
        'status',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function zone_data()
    {
        return $this->hasOne(Zone::class, 'pradesh_id', 'id');
    }
}
