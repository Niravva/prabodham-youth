<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberTag extends Model
{
    use HasFactory;

    protected $table = 'member_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'member_id',
        'tag_id',
        'created_by',
        'created_at',
        'updated_at',
    ];
}
