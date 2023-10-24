<?php

use App\Models\Member;

use App\Models\AdminActionLog;
use App\Models\Country;
use App\Models\Group;
use App\Models\GroupMember;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

define('ATTENDANCE_PERCENTAGE', 36);
define('FRESH_LIMIT', 4);
define('REGULAR_LIMIT', 3);

if (!function_exists('name_prefix_blacklist')) {
    function name_prefix_blacklist_array()
    {
        //return ['bhai', 'lal', 'kumar', 'ben', 'kumari'];
        return ['bhai', 'kumar', 'ben', 'kumari'];
    }
}
if (!function_exists('name_prefix_blacklist_note_html')) {
    function name_prefix_blacklist_note_html()
    {
        return '<div class="text-muted"><small>Please do not use this ' . implode(",", name_prefix_blacklist_array()) . ' ect prefix with the name</small></div>';
    }
}

function get_random_password()
{
    $alphabet = "abcdefghijklmnopqrstuwxyz!@#$%^&*-_+.ABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function add_admin_activity_logs($action_description, $module_name, $action_type, $record_id)
{
    $request = Request();
    $inputs = [
        "country_id" => Auth::user()->country_id,
        "state_id" => Auth::user()->state_id,
        "city_id" => Auth::user()->city_id,
        "pradesh_id" => Auth::user()->pradesh_id,
        "zone_id" => Auth::user()->zone_id,
        "sabha_id" => Auth::user()->sabha_id,
        "group_id" => Auth::user()->group_id,
        "admin_id" => Auth::user()->id,
        "module_name" => $module_name,
        "action_description" => $action_description,
        "action_type" => $action_type,
        "record_id" => $record_id,
        "ip_address" => $request->ip(),
        "user_agent" => $request->userAgent(),
    ];
    $carbonDateTime = Carbon::now();
    $inputs['created_at'] = $carbonDateTime->toDateTimeString();
    $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
    AdminActionLog::create($inputs);
}

if (!function_exists('get_sabha_types')) {
    function get_sabha_types()
    {
        return ['Balika', 'Bal', 'Yuvak', 'Yuvati', 'Vadil', 'Ambrish',  'Karyakarta'];
    }
}
if (!function_exists('get_sabha_occurrences')) {
    function get_sabha_occurrences()
    {
        return ['Never', 'Every 1 Week', 'Every 2 Week'];
    }
}
if (!function_exists('get_sabha_days')) {
    function get_sabha_days()
    {
        return [1 => 'Sunday', 2 => 'Monday', 3 => 'Tuesday', 4 => 'Wednesday', 5 => 'Thursday', 6 => 'Friday', 7 => 'Saturday'];
    }
}
if (!function_exists('get_days')) {
    function get_days()
    {
        $days = [];
        for ($i = 1; $i <= 31; $i++) {
            $days[] = ($i < 10 ? '0' : '') . $i;
        }
        return $days;
    }
}
if (!function_exists('get_months')) {
    function get_months()
    {
        $months = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
        return $months;
    }
}
if (!function_exists('get_sabha_hours')) {
    function get_sabha_hours()
    {
        $hours = [];
        for ($i = 0; $i < 24; $i++) {
            $hours[] = ($i < 10 ? '0' : '') . $i;
        }
        return $hours;
    }
}
if (!function_exists('get_sabha_minutes')) {
    function get_sabha_minutes()
    {
        $minutes = [];
        for ($i = 0; $i < 60; $i++) {
            $minutes[] = ($i < 10 ? '0' : '') . $i;
        }
        return $minutes;
    }
}

if (!function_exists('get_member_types')) {
    function get_member_types()
    {
        return ['Balika', 'Yuvati', 'Bal', 'Yuvak', 'Vadil', 'Vadil_Ambrish', 'Ambrish', 'Ambrish_KK', 'Karya_Karta'];
    }
}

if (!function_exists('get_qualification_list')) {
    function get_qualification_list()
    {
        return [
            'BelowHigherSecondary' => [
                'title' => 'Below Higher Secondary',
                'subjects' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']
            ],
            'HigherSecondary' => [
                'title' => 'Higher Secondary',
                'subjects' => ['Arts', 'Commerce', 'Science', 'Others']
            ],
            'Diploma' => [
                'title' => 'Diploma',
                'subjects' => [
                    'Automobile Engineering',
                    'Chemical Engineering',
                    'Civil Engineering',
                    'Computer Science & Engineering',
                    'Computer Science & Technology',
                    'Computer Technology',
                    'Electrical Engineering',
                    'Electronics Communication Engineering',
                    'Electronics Engineering',
                    'Industrial Electronics',
                    'Information Technology Engineering',
                    'Interior Designing',
                    'Mechanical Draftsman',
                    'Mechanical Engineering',
                    'Others'
                ]
            ],
            'Graduation' => [
                'title' => 'Graduation',
                'subjects' => [
                    'Arts',
                    'Commerce',
                    'Science',
                    'Architecture',
                    'Law',
                    'Management(BMS)',
                    'Medical',
                    'Bachelor of Science in Information Technology (BSc. IT)',
                    'Information Technology Engineering',
                    'Computer Application (BCA)',
                    'Civil Engineering',
                    'Chemical Engineering',
                    'Computer Engineering',
                    'Information Technology Engineering',
                    'Instrumentation Engineering',
                    'Electrical Engineering',
                    'Electronics Engineering',
                    'EXTC Engineering',
                    'Mechanical Engineering',
                    'Production Engineering',
                    'Textile Technology Engineering',
                    'Mass Media (BMM)',
                    'Accounting & Finance (BAF)',
                    'Banking & Insurance (BBI)',
                    'Financial Market (BFM)',
                    'Others'
                ]
            ],
            'PostGraduation' => [
                'title' => 'Post Graduation',
                'subjects' => [
                    'Arts',
                    'Commerce',
                    'Science',
                    'Architecture',
                    'Law',
                    'Management (MBA)',
                    'Medical',
                    'Cost Accountant (ICWA)',
                    'Company Secretary',
                    'Chartered Accountant (CA)',
                    'Chartered Financial Analyst (CFA)',
                    'PhD./ M.Phil',
                    'Master Computer Application (MCA)',
                    'Civil Engineering',
                    'Computer Engineering',
                    'Electrical Engineering',
                    'Electronics Engineering',
                    'Mechanical Engineering',
                    'M. Sc. (Information Technolgy)',
                    'Production Engineering',
                    'Textile Technology Engineering',
                    'EXTC Engineering',
                    'Actuarial Science',
                    'Others'
                ]
            ]
        ];
    }
}

if (!function_exists('get_occupation_list')) {
    function get_occupation_list()
    {
        return ['Student', 'Self employed', 'Service', 'Unemployed', 'Retired', 'Housewife'];
    }
}
if (!function_exists('get_marital_status_list')) {
    function get_marital_status_list()
    {
        return ['Single', 'Married', 'Widow', 'Widower', 'Divorced'];
    }
}
if (!function_exists('get_blood_group_list')) {
    function get_blood_group_list()
    {
        return ['NA', 'AB+', 'AB-', 'A+', 'A-', 'B+', 'B-', 'O+', 'O-'];
    }
}
if (!function_exists('get_language_list')) {
    function get_language_list()
    {
        return ['English', 'Hindi', 'Gujarati', 'Others'];
    }
}
if (!function_exists('current_user_can')) {
    function current_user_can($action, $module, $id = 0)
    {
        if (get_current_admin_level() == 'Super_Admin') {
            return true;
        } else if (get_current_admin_level() == 'Country_Admin') {
            if ($action == 'add_edit_vakta' && $module == 'attendances') {
                return true;
            }
            if ($action == 'cancel_sabha' && $module == 'attendances') {
                return true;
            }
            if ($action == 'edit') {
                return true;
            }
        } else if (get_current_admin_level() == 'State_Admin') {
            if ($action == 'add_edit_vakta' && $module == 'attendances') {
                return true;
            }
            if ($action == 'cancel_sabha' && $module == 'attendances') {
                return true;
            }
            if ($action == 'edit') {
                return true;
            }
        } else if (get_current_admin_level() == 'Pradesh_Admin') {
            if ($action == 'add_edit_vakta' && $module == 'attendances') {
                return true;
            }
            if ($action == 'cancel_sabha' && $module == 'attendances') {
                return true;
            }
            if ($action == 'edit') {
                return true;
            }
        } else if (get_current_admin_level() == 'Zone_Admin') {
            if ($action == 'add_edit_vakta' && $module == 'attendances') {
                return true;
            }
            if ($action == 'cancel_sabha' && $module == 'attendances') {
                return true;
            }
            if ($action == 'edit') {
                return true;
            }
        } else if (get_current_admin_level() == 'Sabha_Admin') {
            if ($action == 'add_edit_vakta' && $module == 'attendances') {
                return true;
            }
            if ($action == 'cancel_sabha' && $module == 'attendances') {
                return true;
            }
            if ($action == 'edit') {
                return true;
            }
        } else if (get_current_admin_level() == 'Group_Admin') {
            if ($action == 'edit') {
                return true;
            }
        } else if (get_current_admin_level() == 'Followup_Admin') {
            if ($action == 'edit' && $module == 'attendances') {
                return true;
            }
            if ($action == 'edit' && $module == 'members') {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('get_admin_type_badge')) {
    function get_admin_type_badge($admin_type)
    {
        if ($admin_type == 'Super_Admin') :
            return '<span class="badge badge-orange">Super_Admin</span>';
        elseif ($admin_type == 'Country_Admin') :
            return '<span class="badge badge-primary">Country_Admin</span>';
        elseif ($admin_type == 'State_Admin') :
            return '<span class="badge badge-info">State_Admin</span>';
        elseif ($admin_type == 'Pradesh_Admin') :
            return '<span class="badge badge-warning">Pradesh_Admin</span>';
        elseif ($admin_type == 'Zone_Admin') :
            return '<span class="badge badge-dark">Zone_Admin</span>';
        elseif ($admin_type == 'Sabha_Admin') :
            return '<span class="badge badge-secondary">Sabha_Admin</span>';
        elseif ($admin_type == 'Group_Admin') :
            return '<span class="badge badge-purple">Group_Admin</span>';
        elseif ($admin_type == 'Followup_Admin') :
            return '<span class="badge badge-cyan">Followup_Admin</span>';
        endif;
    }
}

if (!function_exists('get_admin_for_badge')) {
    function get_admin_for_badge()
    {
        if (get_current_admin_level() == 'Super_Admin') :
            return 'For <span class="badge badge-pill badge-light">System Admin</span>';

        elseif (get_current_admin_level() == 'Country_Admin') :
            $objCountry = get_country_by('id', Auth::user()->country_id);
            return 'For <span class="badge badge-pill badge-light">' . $objCountry->name . '</span>';

        elseif (get_current_admin_level() == 'State_Admin') :
            $objState = get_state_by('id', Auth::user()->state_id);
            return 'For <span class="badge badge-pill badge-light">' . $objState->name . '</span>';

        elseif (get_current_admin_level() == 'Pradesh_Admin') :
            $objPradesh = get_pradesh_by('id', Auth::user()->pradesh_id);
            if ($objPradesh) {
                return 'For <span class="badge badge-pill badge-light">' . $objPradesh->name . '</span>';
            }
            return;

        elseif (get_current_admin_level() == 'Zone_Admin') :
            $objZone = get_zone_by('id', Auth::user()->zone_id);
            if ($objZone) {
                return 'For <span class="badge badge-pill badge-light">' . $objZone->name . '</span>';
            }
            return;

        elseif (get_current_admin_level() == 'Sabha_Admin') :
            $objSabha = get_sabha_by('id', Auth::user()->sabha_id);
            if ($objSabha) {
                return 'For <span class="badge badge-pill badge-light">' . $objSabha->name . '</span>';
            }
            return;

        elseif (get_current_admin_level() == 'Group_Admin') :
            $objGroup = get_group_by('id', Auth::user()->group_id);
            $objSabha = get_sabha_by('id', Auth::user()->sabha_id);
            if ($objGroup) {
                return 'For<br><span class="badge badge-pill badge-light">' . $objSabha->name . ' / ' . $objGroup->name . '</span>';
            }
            if ($objSabha) {
                return 'For <span class="badge badge-pill badge-light">' . $objSabha->name . '</span>';
            }
            return;

        elseif (get_current_admin_level() == 'Followup_Admin') :
            $objSabha = get_sabha_by('id', Auth::user()->sabha_id);
            if ($objSabha) {
                $objGroup = get_group_by('id', Auth::user()->group_id);
                if ($objGroup) {
                    return 'For<br><span class="badge badge-pill badge-light">' . $objSabha->name . ' / ' . $objGroup->name . '</span>';
                }
                return 'For <span class="badge badge-pill badge-light">' . $objSabha->name . '</span>';
            }
            return;

        endif;
    }
}

if (!function_exists('get_status_badge')) {
    function get_status_badge($status)
    {
        if ($status == 'Active') :
            return '<span class="badge badge-success">Active</span>';
        elseif ($status == 'Inactive') :
            return '<span class="badge badge-danger">Inactive</span>';
        endif;
    }
}

if (!function_exists('get_attendance_status_badge')) {
    function get_attendance_status_badge($status)
    {
        if ($status == 'Completed') :
            return '<span class="badge badge-success">Completed</span>';
        elseif ($status == 'Pending') :
            return '<span class="badge badge-warning">Pending</span>';
        elseif ($status == 'No_Vakta_Added') :
            return '<span class="badge badge-info">Add vakta to complete<br>attendance</span>';
        elseif ($status == 'Cancel') :
            return '<span class="badge badge-danger">Cancel</span>';
        endif;
    }
}

if (!function_exists('get_name_badge')) {
    function get_name_badge($name)
    {
        $nameArry = explode(' ', $name);
        $letter = '';
        if (isset($nameArry[0])) {
            $letter .= strtoupper(substr($nameArry[0], 0, 1));
        }
        if (isset($nameArry[2])) {
            $letter .= strtoupper(substr($nameArry[2], 0, 1));
        }
        if ($letter == '') {
            $letter = '?';
        }
        return '<span class="badge bg-purple">' . $letter . '</span>';
    }
}


if (!function_exists('get_current_admin_level')) {
    function get_current_admin_level()
    {
        return Auth::user()->admin_type;
    }
}


if (!function_exists('get_admin_type_list_by_level')) {
    function get_admin_type_list_by_level()
    {
        $admin_types = [];
        if (get_current_admin_level() == 'Super_Admin') :
            $admin_types = ['Country_Admin', 'State_Admin', 'Pradesh_Admin', 'Zone_Admin', 'Sabha_Admin', 'Group_Admin', 'Followup_Admin'];
        elseif (get_current_admin_level() == 'Country_Admin') :
            $admin_types = ['State_Admin', 'Pradesh_Admin', 'Zone_Admin', 'Sabha_Admin', 'Group_Admin', 'Followup_Admin'];
        elseif (get_current_admin_level() == 'State_Admin') :
            $admin_types = ['Pradesh_Admin', 'Zone_Admin', 'Sabha_Admin', 'Group_Admin', 'Followup_Admin'];
        elseif (get_current_admin_level() == 'Pradesh_Admin') :
            $admin_types = ['Zone_Admin', 'Sabha_Admin', 'Group_Admin', 'Followup_Admin'];
        elseif (get_current_admin_level() == 'Zone_Admin') :
            $admin_types = ['Sabha_Admin', 'Group_Admin', 'Followup_Admin'];
        elseif (get_current_admin_level() == 'Sabha_Admin') :
            $admin_types = ['Group_Admin', 'Followup_Admin'];
        elseif (get_current_admin_level() == 'Group_Admin') :
            $admin_types = ['Followup_Admin'];
        endif;

        return $admin_types;
    }
}

if (!function_exists('get_country_list_by_level')) {
    function get_country_list_by_level()
    {
        $country_list = [];
        $country_list = DB::select('select * from countries where 1=1 order by name');
        return $country_list;
    }
}

if (!function_exists('get_state_list_by_level')) {
    function get_state_list_by_level()
    {
        $state_list = [];
        if (get_current_admin_level() == 'Super_Admin') :
            $state_list = DB::select('select * from states where 1=1 order by name');

        elseif (get_current_admin_level() == 'Country_Admin') :
            $state_list = DB::select('select * from states where country_id = ' . Auth::user()->country_id . ' order by name');

        endif;

        return $state_list;
    }
}

if (!function_exists('get_pradesh_list_by_level')) {
    function get_pradesh_list_by_level()
    {
        $pradesh_list = [];
        if (get_current_admin_level() == 'Super_Admin') :
            $pradesh_list = DB::select('select *,(select name from states where id = p.state_id) as state_name,(select name from cities where id = p.city_id) as city_name from pradeshs as p where 1=1');

        elseif (get_current_admin_level() == 'Country_Admin') :
            $pradesh_list = DB::select('select *,(select name from states where id = p.state_id) as state_name,(select name from cities where id = p.city_id) as city_name from pradeshs as p where p.country_id = ' . Auth::user()->country_id);

        elseif (get_current_admin_level() == 'State_Admin') :
            $pradesh_list = DB::select('select *,(select name from states where id = p.state_id) as state_name,(select name from cities where id = p.city_id) as city_name from pradeshs as p where p.country_id = ' . Auth::user()->country_id . ' and p.state_id = ' . Auth::user()->state_id);
        endif;

        return $pradesh_list;
    }
}


if (!function_exists('get_object_hierarchy_info')) {
    function get_object_hierarchy_info($object)
    {
        $info = '';
        if (in_array(get_current_admin_level(), ['Sabha_Admin', 'Group_Admin', 'Followup_Admin'])) {
            if ($object->group_id != NULL) {
                $objGroup = get_group_by('id', $object->group_id);
                if ($objGroup) {
                    $info = $objGroup->name;
                }
            }
            return $info;
        }

        //Zone admin
        if (get_current_admin_level() == 'Zone_Admin') {
            if ($object->sabha_id != NULL) {
                $objSabha = get_sabha_by('id', $object->sabha_id);
                if ($objSabha) {
                    $info = $objSabha->name;
                }
            }
            if ($object->group_id != NULL) {
                $objGroup = get_group_by('id', $object->group_id);
                if ($objGroup) {
                    $info .= ' / ' . $objGroup->name;
                }
            }
            return $info;
        }

        //Pradesh admin
        if (get_current_admin_level() == 'Pradesh_Admin') {
            if ($object->zone_id != NULL) {
                $objZone = get_zone_by('id', $object->zone_id);
                if ($objZone) {
                    $info = $objZone->name;
                }
            }
            if ($object->sabha_id != NULL) {
                $objSabha = get_sabha_by('id', $object->sabha_id);
                if ($objSabha) {
                    $info .= ' / ' . $objSabha->name;
                }
            }
            if ($object->group_id != NULL) {
                $objGroup = get_group_by('id', $object->group_id);
                if ($objGroup) {
                    $info .= ' / ' . $objGroup->name;
                }
            }
            return $info;
        }

        //State admin
        if (get_current_admin_level() == 'State_Admin') {
            if ($object->pradesh_id != NULL) {
                $objPradesh = get_pradesh_by('id', $object->pradesh_id);
                if ($objPradesh) {
                    $info = $objPradesh->name;
                }
            }
            if ($object->zone_id != NULL) {
                $objZone = get_zone_by('id', $object->zone_id);
                if ($objZone) {
                    $info .= ' / ' . $objZone->name;
                }
            }
            if ($object->sabha_id != NULL) {
                $objSabha = get_sabha_by('id', $object->sabha_id);
                if ($objSabha) {
                    $info .= ' / ' . $objSabha->name;
                }
            }
            if ($object->group_id != NULL) {
                $objGroup = get_group_by('id', $object->group_id);
                if ($objGroup) {
                    $info .= ' / ' . $objGroup->name;
                }
            }
            return $info;
        }

        if ($object->country_id != NULL) {
            $objCountry = get_country_by('id', $object->country_id);
            if ($objCountry) {
                $info = $objCountry->name;
            }
        }
        if ($object->state_id != NULL) {
            $objState = get_state_by('id', $object->state_id);
            if ($objState) {
                $info .= ' / ' . $objState->name;
            }
        }
        if ($object->pradesh_id != NULL) {
            $objPradesh = get_pradesh_by('id', $object->pradesh_id);
            if ($objPradesh) {
                $info .= ' / ' . $objPradesh->name;
            }
        }
        if ($object->zone_id != NULL) {
            $objZone = get_zone_by('id', $object->zone_id);
            if ($objZone) {
                $info .= ' / ' . $objZone->name;
            }
        }
        if ($object->sabha_id != NULL) {
            $objSabha = get_sabha_by('id', $object->sabha_id);
            if ($objSabha) {
                $info .= ' / ' . $objSabha->name;
            }
        }
        if ($object->group_id != NULL) {
            $objGroup = get_group_by('id', $object->group_id);
            if ($objGroup) {
                $info .= ' / ' . $objGroup->name;
            }
        }

        return $info;
    }
}


if (!function_exists('get_created_by_name')) {
    function get_created_by_name($id, $show_hierarchy = false)
    {
        $objAdmin = get_admin_by('id', $id);
        $info = '';
        if (isset($objAdmin->name)) {
            if (Auth::user()->id == $objAdmin->id) {
                return 'You';
            }
            if ($show_hierarchy === true) {
                $info = get_object_hierarchy_info($objAdmin);
                $info = '<br><small class="text-muted" style="font-size: 75%;">' . $info . '</small>';
            }
            return $objAdmin->name . ' ' . get_admin_type_badge($objAdmin->admin_type) . $info;
        }
        return;
    }
}

if (!function_exists('get_admin_name')) {
    function get_admin_name($id)
    {
        $objAdmin = get_admin_by('id', $id);
        if (isset($objAdmin->name)) {
            return $objAdmin->name;
        }
        return;
    }
}

if (!function_exists('get_group_admin_name')) {
    function get_group_admin_name($group_id)
    {
        $name = '';
        $admins = DB::table('admins')->where('group_id', $group_id)->where('admin_type', 'Group_Admin')->get();
        if ($admins) {
            $name .= '<ul class="mb-0">';
            foreach ($admins as $admin) {
                $name .= '<li class="mb-1">' . $admin->name . '</li>';
            }
            $name .= '</ul>';
        }
        return $name;
    }
}

if (!function_exists('get_group_followup_admin_name')) {
    function get_group_followup_admin_name($group_id)
    {
        $name = '';
        $admins = DB::table('admins')->where('group_id', $group_id)->where('admin_type', 'Followup_Admin')->get();
        if ($admins) {
            $name .= '<ul class="mb-0">';
            foreach ($admins as $admin) {
                $name .= '<li class="mb-1">' . $admin->name . '</li>';
            }
            $name .= '</ul>';
        }
        return $name;
    }
}


if (!function_exists('get_member_type_badge')) {
    function get_member_type_badge($type)
    {
        if ($type == 'Ambrish' || $type == 'Ambrish_KK' || $type == 'Vadil_Ambrish') {
            return '<span class="badge badge-pill bg-lightblue">' . $type . '</span>';
        }
        if ($type == 'Karya_Karta') {
            return '<span class="badge badge-pill bg-olive">Karya_Karta</span>';
        }

        return '<span class="badge badge-pill bg-gray">' . $type . '</span>';
    }
}
if (!function_exists('get_member_fullname')) {
    function get_member_fullname($id, $with_type = false, $with_zone = false)
    {
        if ($id == 0) {
            return '';
        }
        if ($id == NULL) {
            return '';
        }
        $objMember = get_member_by('id', $id);
        if ($objMember) {
            $name = ucwords($objMember->first_name . ' ' . $objMember->middle_name . ' ' . $objMember->surname);
            if ($with_zone === true) {
                $objZone = get_zone_by('id', $objMember->zone_id);
                if (isset($objZone->name)) {
                    $name .= ' (' . $objZone->name . ')';
                }
            }
            if ($with_type === true) {
                $name .= ' ' . get_member_type_badge($objMember->member_is);
            }
            return $name;
        }
        return;
    }
}

if (!function_exists('get_my_followup_memner_ids')) {
    function get_my_followup_memner_ids($admin_id = 0)
    {
        if ($admin_id > 0) {
            return Member::where('follow_up_by', '=', $admin_id)->pluck('id')->toArray();
        }
        return Member::where('follow_up_by', '=', Auth::user()->id)->pluck('id')->toArray();
    }
}

if (!function_exists('get_my_memner_ids_by_group')) {
    function get_my_memner_ids_by_group($group_id)
    {
        return Member::where('group_id', '=', $group_id)->pluck('id')->toArray();
    }
}

/*=================== get by ======================= */
if (!function_exists('get_admin_by')) {
    function get_admin_by($field, $value)
    {
        $admin = DB::table('admins')->where($field, $value)->first();
        return $admin;
    }
}
if (!function_exists('get_member_by')) {
    function get_member_by($field, $value)
    {
        $member = DB::table('members')->where($field, $value)->first();
        return $member;
    }
}
if (!function_exists('get_country_by')) {
    function get_country_by($field, $value)
    {
        $country = DB::table('countries')->where($field, $value)->first();
        return $country;
    }
}
if (!function_exists('get_state_by')) {
    function get_state_by($field, $value)
    {
        $state = DB::table('states')->where($field, $value)->first();
        return $state;
    }
}
if (!function_exists('get_city_by')) {
    function get_city_by($field, $value)
    {
        $city = DB::table('cities')->where($field, $value)->first();
        return $city;
    }
}
if (!function_exists('get_pradesh_by')) {
    function get_pradesh_by($field, $value)
    {
        $pradesh = DB::table('pradeshs')->where($field, $value)->first();
        return $pradesh;
    }
}
if (!function_exists('get_zone_by')) {
    function get_zone_by($field, $value)
    {
        $zone = DB::table('zones')->where($field, $value)->first();
        return $zone;
    }
}
if (!function_exists('get_sabha_by')) {
    function get_sabha_by($field, $value)
    {
        $sabha = DB::table('sabhas')->where($field, $value)->first();
        return $sabha;
    }
}
if (!function_exists('get_group_by')) {
    function get_group_by($field, $value)
    {
        $group = DB::table('groups')->where($field, $value)->first();
        return $group;
    }
}

if (!function_exists('get_group_name')) {
    function get_group_name($id)
    {
        $objGroup = get_group_by('id', $id);
        if (isset($objGroup->name)) {
            return $objGroup->name;
        }
    }
}

if (!function_exists('get_attendance_present_count')) {
    function get_attendance_present_count($attendance_id)
    {
        $attenders = DB::table('attenders')->where('attendance_id', $attendance_id)->where('present', 'Yes')->count();
        return $attenders;
    }
}
if (!function_exists('get_attendance_absence_count')) {
    function get_attendance_absence_count($attendance_id)
    {
        $attenders = DB::table('attenders')->where('attendance_id', $attendance_id)->where('present', 'No')->count();
        return $attenders;
    }
}

if (!function_exists('get_member_attendance_absence_count')) {
    function get_member_attendance_absence_count($member_id)
    {
        $absence_count = DB::table('attenders')->where('member_id', $member_id)->where('present', 'No')->count();
        return $absence_count;
    }
}

if (!function_exists('get_member_attendance_last_absence_count')) {
    function get_member_attendance_last_absence_count($member_id)
    {
        $attenders = DB::table('attenders')->where('member_id', $member_id)->orderBy('created_at', 'DESC')->get();

        if (isset($attenders[0]) && $attenders[0]->present == "Yes") {
            return 0;
        }

        $count = 0;
        foreach ($attenders as $attender) {
            if ($attender->present == "No") {
                $count++;
            } else {
                break;
            }
        }
        return $count;
    }
}

if (!function_exists('get_calculate_attendance_percentage')) {
    function get_calculate_attendance_percentage($attendance_id)
    {
        $total = DB::table('attenders')->where('attendance_id', $attendance_id)->count();
        $present = get_attendance_present_count($attendance_id);
        if ($present > 0) {
            //Percentage formula = (Value/Total value) × 100
            $percentage = ($present / $total) * 100;
            return number_format($percentage);
        }
        return 0;
    }
}

if (!function_exists('add_attendance_when_member_add_or_update')) {
    function add_attendance_when_member_add_or_update($member_id)
    {
        $objMember = get_member_by('id', $member_id);
        if (strtolower($objMember->attending_sabha) == "yes") {
            $query = "SELECT * FROM `attenders` WHERE `member_id`= $objMember->id AND `attendance_id` = (SELECT id FROM `attendances` WHERE `sabha_id`= $objMember->sabha_id ORDER by created_at DESC LIMIT 1)";
            $results = DB::select($query);
            if (count($results) == 0) {
                $carbonDateTime = Carbon::now();

                $results2 = DB::select("SELECT id FROM `attendances` WHERE `sabha_id`= $objMember->sabha_id ORDER by created_at DESC LIMIT 1");
                if (isset($results2[0])) {
                    $attendance_id = $results2[0]->id;

                    DB::insert("INSERT INTO `attenders`(`id`, `country_id`, `state_id`, `city_id`, `pradesh_id`, `zone_id`, `sabha_id`, `attendance_id`, `member_id`, `present`, `attendance_by`, `created_at`, `updated_at`) VALUES (NULL, $objMember->country_id, $objMember->state_id, $objMember->city_id, $objMember->pradesh_id, $objMember->zone_id, $objMember->id, $attendance_id, $objMember->id, 'No','0','" . $carbonDateTime->toDateTimeString() . "','" . $carbonDateTime->toDateTimeString() . "')");
                }
            }
        }
    }
}

if (!function_exists('get_member_last_attended')) {
    function get_member_last_attended($member)
    {
        $query = "SELECT * FROM `attenders` WHERE `member_id`= $member->id AND `present`='Yes' ORDER BY `created_at` DESC LIMIT 1";
        $results = DB::select($query);
        if (isset($results[0])) {
            return date('j M, Y', strtotime($results[0]->created_at));
        }
        return "N/A";
    }
}

if (!function_exists('get_member_total_attendance')) {
    function get_member_total_attendance($member)
    {
        $query = "SELECT COUNT(*) as total FROM `attenders` WHERE `member_id`= $member->id";
        $results = DB::select($query);
        return isset($results[0]) ? $results[0]->total : 0;
    }
}

if (!function_exists('get_member_total_present_attendance')) {
    function get_member_total_present_attendance($member)
    {
        $query = "SELECT COUNT(*) as total FROM `attenders` WHERE `member_id`= $member->id AND `present` = 'Yes'";
        $results = DB::select($query);
        return isset($results[0]) ? $results[0]->total : 0;
    }
}

if (!function_exists('get_member_attendance_percentage')) {
    function get_member_attendance_percentage($member)
    {
        if (get_member_total_present_attendance($member) == 0 || get_member_total_attendance($member)) {
            return 0;
        }
        return (get_member_total_present_attendance($member) / get_member_total_attendance($member)) * 100;
    }
}

if (!function_exists('birthday_loop_itm_html')) {
    function birthday_loop_itm_html($member)
    {
?>
        <li data-yuvakname="<?php echo $member->first_name ?> <?php echo $member->surname ?>">

            <div class="checkbox-select-yuvak-pos member_checkbox_wrap d-none">
                <div class="icheck-primary">
                    <input id="checkbox-member-<?= $member->id; ?>" type="checkbox" data-member_name="<?php echo $member->first_name ?> <?php echo $member->surname ?>" data-mobile="<?php echo $member->mobile ?>" data-zone_name="<?php echo $member->zoneName ?>" data-sabha_name="<?php echo $member->sabhaName ?>" data-attending_sabha="<?php echo $member->attending_sabha ?>">
                    <label for="checkbox-member-<?= $member->id; ?>"> </label>
                </div>
            </div>

            <a data-toggle="modal" data-target="#modal-member-view" data-remote="<?php echo route("members.show", $member->id); ?>" href="javascript:void;" class="d-block">
                <div class="row align-items-center">
                    <div class="col-2 col-md-1 text-center">
                        <?php
                        $_photoUrl = asset('assets/img/yuvak-placehoder.png');
                        if ($member->photo) {
                            $_photoUrl = url('uploads/member_photo') . '/' . $member->photo;
                        }
                        ?>
                        <img class="img-yuvak-list" src="<?php echo $_photoUrl; ?>" alt="yuvak">
                    </div>
                    <div class="col-9 col-md-10">
                        <div class="text-muted" style="font-size: 12px;">
                            <span><?php echo date('jS F, Y', strtotime($member->date_of_birth)); ?></span>
                        </div>
                        <p class="mb-0">
                            <strong class="yuvak-name"><?php echo strtoupper($member->first_name . ' ' . $member->middle_name . ' ' . $member->surname); ?></strong>
                            <?php echo get_member_type_badge($member->member_is); ?>
                        </p>
                        <?php
                        $zone = get_zone_by('id', $member->zone_id);
                        $sabha = get_sabha_by('id', $member->sabha_id);
                        ?>
                        <p class="mb-0 text-muted">
                            <?php if ($zone) {
                                echo $zone->name;
                            } ?> | <?php if ($sabha) {
                                        echo $sabha->name;
                                    } ?>
                            |
                            <?php if ($member->attending_sabha == "Yes") { ?>
                                <small class="badge badge-success" style="font-size: 80%;">Yes</small>
                            <?php } else { ?>
                                <small class="badge badge-danger" style="font-size: 80%;">No</small>
                            <?php } ?>
                        </p>
                    </div>
                    <div class="col-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </div>
            </a>
        </li>
<?php
    }
}


if (!function_exists('get_zone_attendance_data')) {
    function get_zone_attendance_data($zone_id)
    {
        $query = "SELECT (SELECT COUNT(*) FROM `attendances` WHERE zone_id = $zone_id) as total, (SELECT COUNT(*) FROM `attendances` WHERE zone_id = $zone_id and status = 'pending') as pending, (SELECT COUNT(*) FROM `attendances` WHERE zone_id = $zone_id and status = 'Completed') as completed FROM `attendances` WHERE zone_id = $zone_id group BY zone_id";
        $results = DB::select($query);
        return [
            "total" => isset($results[0]->total) ? $results[0]->total : 0,
            "pending" => isset($results[0]->pending) ? $results[0]->pending : 0,
            "completed" => isset($results[0]->completed) ? $results[0]->completed : 0,
        ];
    }
}
if (!function_exists('get_sabha_attendance_data')) {
    function get_sabha_attendance_data($sabha_id)
    {
        $query = "SELECT (SELECT COUNT(*) FROM `attendances` WHERE sabha_id = $sabha_id) as total, (SELECT COUNT(*) FROM `attendances` WHERE sabha_id = $sabha_id and status = 'pending') as pending, (SELECT COUNT(*) FROM `attendances` WHERE sabha_id = $sabha_id and status = 'Completed') as completed FROM `attendances` WHERE sabha_id = $sabha_id group BY sabha_id";
        $results = DB::select($query);
        return [
            "total" => isset($results[0]->total) ? $results[0]->total : 0,
            "pending" => isset($results[0]->pending) ? $results[0]->pending : 0,
            "completed" => isset($results[0]->completed) ? $results[0]->completed : 0,
        ];
    }
}

if (!function_exists('get_member_phonecode')) {
    function get_member_phonecode($country_id)
    {
        $country = Country::where('id', '=', $country_id)->first();
        if ($country) {
            return $country->phonecode;
        } else {
            return 91;
        }
    }
}
