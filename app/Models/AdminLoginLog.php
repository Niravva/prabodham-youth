<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLoginLog extends Model
{
    use HasFactory;

    protected $table = 'admin_login_logs';

    protected $fillable = [
        "country_id",
        "state_id",
        "city_id",
        "pradesh_id",
        "zone_id",
        "sabha_id",
        "group_id",
        "admin_id",
        "action_type",
        "location",
        "ip_address",
        "user_agent",
        'created_at',
        'updated_at',
    ];
}
