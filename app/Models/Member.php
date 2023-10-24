<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

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
        'group_id',
        'follow_up_by',
        'first_name',
        'middle_name',
        'surname',
        'email',
        'mobile',
        'nick_name',
        'photo',
        'gender',
        'date_of_birth',
        'flat_no',
        'building_name',
        'landmark',
        'street_name',
        'postcode',
        'home_phone',
        'office_phone',
        'edu_qualification',
        'edu_subject',
        'edu_other',
        'edu_status',
        'occupation',
        'school_college',
        'organization',
        'industry',
        'designation',
        'marital_status',
        'anniversery_date',
        'blood_group',
        'performing_puja',
        'nishtawan',
        'ambrish_code',
        'ambrish_diksha_year',
        'languages_known',
        'member_is',
        'reference_id',
        'attending_sabha',
        'joining_date',
        'status',
        'created_by',
        'ref_name',
        'avd_id',
        'created_at',
        'updated_at',
    ];
}
