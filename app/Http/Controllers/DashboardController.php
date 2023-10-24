<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (get_current_admin_level() == 'Super_Admin') :
            return view('dashboard.dashboard');

        elseif (get_current_admin_level() == 'Country_Admin') :
            return view('dashboard.country_admin');

        elseif (get_current_admin_level() == 'State_Admin') :
            return view('dashboard.state_admin');

        elseif (get_current_admin_level() == 'Pradesh_Admin') :
            $request->id = Auth::user()->pradesh_id;
            return $this->pradeshDetail($request);

        elseif (get_current_admin_level() == 'Zone_Admin') :
            $request->id = Auth::user()->zone_id;
            return $this->zoneDetail($request);

        elseif (get_current_admin_level() == 'Sabha_Admin') :
            $request->id = Auth::user()->sabha_id;
            return $this->sabhaDetail($request);

        elseif (get_current_admin_level() == 'Group_Admin') :
            $request->id = Auth::user()->group_id;
            return $this->groupDetail($request);

        elseif (get_current_admin_level() == 'Followup_Admin') :
            $request->id = Auth::user()->id;
            return $this->followupkaryakartaDetail($request);

        endif;
    }

    public function pradeshDetail(Request $request)
    {
        $dashboardData = [];
        $_pradeshId = $request->id;
        $dashboardData['pradesh'] = (array) get_pradesh_by('id', $_pradeshId);
        $totalZone = DB::table('zones')->where('pradesh_id', $_pradeshId)->count();
        $totalSabha = DB::table('sabhas')->where('pradesh_id', $_pradeshId)->where('status', 'Active')->where('occurance', '!=', 'Never')->count();
        $totalYuvak = DB::table('members')->where('pradesh_id', $_pradeshId)->where('attending_sabha', 'Yes')->count();


        //ATTENDANCE PENDING
        $pendingAttendanceResults = $this->get_pending_attendance(" AND a1.pradesh_id = $_pradeshId ");
        //REGULAR
        $regularResults = $this->get_regular(" AND a1.pradesh_id = $_pradeshId ");
        //FRESH
        $freshResults = $this->get_fresh(" AND a1.pradesh_id = $_pradeshId ");

        //BIRTHDAY
        $todayBirthday = $this->get_birthday("AND pradesh_id = $_pradeshId", "today");
        $tomorrowBirthday = $this->get_birthday("AND pradesh_id = $_pradeshId", "tomorrow");
        $thisWeekBirthday = $this->get_birthday("AND pradesh_id = $_pradeshId", "this-week");
        $thisMonthBirthday = $this->get_birthday("AND pradesh_id = $_pradeshId", "this-month");

        //SABHA
        $todaySabha = $this->get_sabha("AND pradesh_id = $_pradeshId", "today");
        $tomorrowSabha = $this->get_sabha("AND pradesh_id = $_pradeshId", "tomorrow");
        $thiWeekSabha = $this->get_sabha("AND pradesh_id = $_pradeshId", "this-week");

        //NEW JOINEE
        $weeklyNewJoinee = $this->get_newJoinee("AND pradesh_id = $_pradeshId", "weekly");
        $monthlyNewJoinee = $this->get_newJoinee("AND pradesh_id = $_pradeshId", "monthly");
        $quartelyNewJoinee = $this->get_newJoinee("AND pradesh_id = $_pradeshId", "quartely");
        $yearlyNewJoinee = $this->get_newJoinee("AND pradesh_id = $_pradeshId", "yearly");

        //Pradesh statistics
        $dashboardData['totalZone'] = $totalZone;
        $dashboardData['totalSabha'] = $totalSabha;
        $dashboardData['pendingAttendance'] = count($pendingAttendanceResults);
        //Youth Summary
        $dashboardData['youthSummary']['total'] =  $totalYuvak;
        $dashboardData['youthSummary']['regular'] = count($regularResults);
        $dashboardData['youthSummary']['irregular'] = ($totalYuvak - count($regularResults));
        $dashboardData['youthSummary']['fresh'] = count($freshResults);
        //Birthday
        $dashboardData['todayBirthday'] = count($todayBirthday);
        $dashboardData['tomorrowBirthday'] = count($tomorrowBirthday);
        $dashboardData['thisWeekBirthday'] = count($thisWeekBirthday);
        $dashboardData['thisMonthBirthday'] = count($thisMonthBirthday);
        //Sabha
        $dashboardData['todaySabha'] = count($todaySabha);
        $dashboardData['tomorrowSabha'] = count($tomorrowSabha);
        $dashboardData['thiWeekSabha'] = count($thiWeekSabha);
        //New joinee
        $dashboardData['weeklyNewJoinee'] = $weeklyNewJoinee;
        $dashboardData['monthlyNewJoinee'] = $monthlyNewJoinee;
        $dashboardData['quartelyNewJoinee'] = $quartelyNewJoinee;
        $dashboardData['yearlyNewJoinee'] = $yearlyNewJoinee;


        return view('dashboard.pradesh_detail')->with('data', $dashboardData)->with('request', $request);
    }

    public function zoneList(Request $request)
    {
        $dashboardData = [];
        $pradeshName = get_pradesh_by('id', $request->id)->name;
        $zones =  DB::table('zones')->where('pradesh_id', $request->id)->orderBy('name', 'asc')->get()->toArray();
        foreach ($zones as $zone) {
            $_zoneId = $zone->id;
            $dashboardData[$_zoneId] = (array) $zone;

            //
            $zoneMemberCount = DB::table('members')->where('zone_id', $_zoneId)->count();
            $zoneRegularResults = $this->get_regular(" AND a1.zone_id = $_zoneId ");
            $dashboardData[$_zoneId]['total_youth'] = $zoneMemberCount;
            $dashboardData[$_zoneId]['regular_youth'] = count($zoneRegularResults);
            $dashboardData[$_zoneId]['irregular_youth'] = ($zoneMemberCount - count($zoneRegularResults));
        }

        return view('dashboard.zone_list')->with('data', $dashboardData)->with('request', $request)->with('pradeshName', $pradeshName);
    }

    public function zoneDetail(Request $request)
    {
        $_zoneId = $request->id;
        $dashboardData[$_zoneId] = (array) get_zone_by('id', $_zoneId);
        $sabhas = DB::table('sabhas')->where('zone_id', $_zoneId)->where('status', 'Active')->orderBy('name', 'asc')->get();
        //Zone total youths
        $zoneMemberCount = DB::table('members')->where('zone_id', $_zoneId)->count();

        //ATTENDANCE PENDING
        $zonePendingAttendanceResults = $this->get_pending_attendance(" AND a1.zone_id = $_zoneId");
        //REGULAR
        $zoneRegularResults = $this->get_regular(" AND a1.zone_id = $_zoneId ");
        //FRESH
        $zoneFreshResults = $this->get_fresh(" AND a1.zone_id = $_zoneId ");


        //BIRTHDAY
        $todayBirthday = $this->get_birthday("AND zone_id = $_zoneId", "today");
        $tomorrowBirthday = $this->get_birthday("AND zone_id = $_zoneId", "tomorrow");
        $thisWeekBirthday = $this->get_birthday("AND zone_id = $_zoneId", "this-week");
        $thisMonthBirthday = $this->get_birthday("AND zone_id = $_zoneId", "this-month");

        //SABHA
        $todaySabha = $this->get_sabha("AND zone_id = $_zoneId", "today");
        $tomorrowSabha = $this->get_sabha("AND zone_id = $_zoneId", "tomorrow");
        $thiWeekSabha = $this->get_sabha("AND zone_id = $_zoneId", "this-week");

        //NEW JOINEE
        $weeklyNewJoinee = $this->get_newJoinee("AND zone_id = $_zoneId", "weekly");
        $monthlyNewJoinee = $this->get_newJoinee("AND zone_id = $_zoneId", "monthly");
        $quartelyNewJoinee = $this->get_newJoinee("AND zone_id = $_zoneId", "quartely");
        $yearlyNewJoinee = $this->get_newJoinee("AND zone_id = $_zoneId", "yearly");



        //Zone statistics
        $dashboardData['totalSabha'] = count($sabhas);
        $dashboardData['pendingAttendance'] = count($zonePendingAttendanceResults);
        //Youth Summary
        $dashboardData['youthSummary']['total'] = $zoneMemberCount;
        $dashboardData['youthSummary']['regular'] = count($zoneRegularResults);
        $dashboardData['youthSummary']['irregular'] = ($zoneMemberCount - count($zoneRegularResults));
        $dashboardData['youthSummary']['fresh'] = count($zoneFreshResults);
        //Birthday
        $dashboardData['todayBirthday'] = count($todayBirthday);
        $dashboardData['tomorrowBirthday'] = count($tomorrowBirthday);
        $dashboardData['thisWeekBirthday'] = count($thisWeekBirthday);
        $dashboardData['thisMonthBirthday'] = count($thisMonthBirthday);
        //Sabha
        $dashboardData['todaySabha'] = count($todaySabha);
        $dashboardData['tomorrowSabha'] = count($tomorrowSabha);
        $dashboardData['thiWeekSabha'] = count($thiWeekSabha);
        //New joinee
        $dashboardData['weeklyNewJoinee'] = $weeklyNewJoinee;
        $dashboardData['monthlyNewJoinee'] = $monthlyNewJoinee;
        $dashboardData['quartelyNewJoinee'] = $quartelyNewJoinee;
        $dashboardData['yearlyNewJoinee'] = $yearlyNewJoinee;


        return view('dashboard.zone_detail')->with('data', $dashboardData)->with('request', $request);
    }

    public function sabhaList(Request $request)
    {
        $dashboardData = [];
        $zoneName = get_zone_by('id', $request->id)->name;
        $sabhas =  DB::table('sabhas')->where('zone_id', $request->id)->where('status', 'Active')->orderBy('name', 'asc')->get()->toArray();
        foreach ($sabhas as $sabha) {
            $_sabhaId = $sabha->id;
            $dashboardData[$_sabhaId] = (array) $sabha;

            $sabhaMemberCount = DB::table('members')->where('sabha_id', $_sabhaId)->count();
            $sabhaRegularResults = $this->get_regular(" AND a1.sabha_id = $_sabhaId ");
            $dashboardData[$_sabhaId]['total_youth'] = $sabhaMemberCount;
            $dashboardData[$_sabhaId]['regular_youth'] = count($sabhaRegularResults);
            $dashboardData[$_sabhaId]['irregular_youth'] = ($sabhaMemberCount - count($sabhaRegularResults));
        }
        return view('dashboard.sabha_list')->with('data', $dashboardData)->with('request', $request)->with('zoneName', $zoneName);
    }

    public function sabhaDetail(Request $request)
    {
        $dashboardData = [];

        $_sabhaId = $request->id;
        $dashboardData[$_sabhaId] = (array) get_sabha_by('id', $_sabhaId);
        $dashboardData[$_sabhaId]['zoneName'] = get_zone_by('id', $dashboardData[$_sabhaId]['zone_id'])->name;
        $sabhaMemberCount = DB::table('members')->where('sabha_id', $_sabhaId)->count();
        $groups = DB::table('groups')->where('sabha_id', $_sabhaId)->where('status', 'Active')->orderBy('name', 'asc')->get();

        //ATTENDANCE PENDING
        $sabhaPendingAttendanceResults = $this->get_pending_attendance(" AND a1.sabha_id = $_sabhaId");
        //REGULAR
        $sabhaRegularResults = $this->get_regular(" AND a1.sabha_id = $_sabhaId ");
        //FRESH
        $sabhaFreshResults = $this->get_fresh(" AND a1.sabha_id = $_sabhaId ");

        //BIRTHDAY
        $todayBirthday = $this->get_birthday("AND sabha_id = $_sabhaId", "today");
        $tomorrowBirthday = $this->get_birthday("AND sabha_id = $_sabhaId", "tomorrow");
        $thisWeekBirthday = $this->get_birthday("AND sabha_id = $_sabhaId", "this-week");
        $thisMonthBirthday = $this->get_birthday("AND sabha_id = $_sabhaId", "this-month");

        //NEW JOINEE
        $weeklyNewJoinee = $this->get_newJoinee("AND sabha_id = $_sabhaId", "weekly");
        $monthlyNewJoinee = $this->get_newJoinee("AND sabha_id = $_sabhaId", "monthly");
        $quartelyNewJoinee = $this->get_newJoinee("AND sabha_id = $_sabhaId", "quartely");
        $yearlyNewJoinee = $this->get_newJoinee("AND sabha_id = $_sabhaId", "yearly");



        //Sabha statistics
        $dashboardData['totalGroup'] = count($groups);
        $dashboardData['pendingAttendance'] = count($sabhaPendingAttendanceResults);
        //Youth Summary
        $dashboardData['youthSummary']['total'] = $sabhaMemberCount;
        $dashboardData['youthSummary']['regular'] = count($sabhaRegularResults);
        $dashboardData['youthSummary']['irregular'] = ($sabhaMemberCount - count($sabhaRegularResults));
        $dashboardData['youthSummary']['fresh'] = count($sabhaFreshResults);
        //Birthday
        $dashboardData['todayBirthday'] = count($todayBirthday);
        $dashboardData['tomorrowBirthday'] = count($tomorrowBirthday);
        $dashboardData['thisWeekBirthday'] = count($thisWeekBirthday);
        $dashboardData['thisMonthBirthday'] = count($thisMonthBirthday);
        //New joinee
        $dashboardData['weeklyNewJoinee'] = $weeklyNewJoinee;
        $dashboardData['monthlyNewJoinee'] = $monthlyNewJoinee;
        $dashboardData['quartelyNewJoinee'] = $quartelyNewJoinee;
        $dashboardData['yearlyNewJoinee'] = $yearlyNewJoinee;


        return view('dashboard.sabha_detail')->with('data', $dashboardData)->with('request', $request);
    }

    public function groupList(Request $request)
    {
        $dashboardData = [];
        $sabha = get_sabha_by('id', $request->id);
        $sabhaName = get_zone_by('id', $sabha->zone_id)->name . ' | ' . $sabha->name;

        $groups =  DB::table('groups')->where('sabha_id', $request->id)->where('status', 'Active')->orderBy('name', 'asc')->get()->toArray();
        foreach ($groups as $group) {
            $_groupId = $group->id;
            $dashboardData[$_groupId] = (array) $group;

            //
            $groupMemberCount = DB::table('members')->where('group_id', $_groupId)->count();
            $groupRegularResults = $this->get_regular(" AND a1.sabha_id = $request->id AND a1.member_id IN(SELECT id FROM `members` WHERE `group_id` = $_groupId)");
            $dashboardData[$_groupId]['total_youth'] = $groupMemberCount;
            $dashboardData[$_groupId]['regular_youth'] = count($groupRegularResults);
            $dashboardData[$_groupId]['irregular_youth'] = ($groupMemberCount - count($groupRegularResults));
        }

        return view('dashboard.group_list')->with('data', $dashboardData)->with('request', $request)->with('sabhaName', $sabhaName);
    }

    public function groupDetail(Request $request)
    {
        $dashboardData = [];

        $_groupId = $request->id;
        $dashboardData[$_groupId] = (array) get_group_by('id', $_groupId);
        $_sabhaId = $dashboardData[$_groupId]['sabha_id'];
        $dashboardData[$_groupId]['sabhaName'] = get_sabha_by('id', $_sabhaId)->name;
        $followUpKaryakarta = DB::table('admins')->where('group_id', $_groupId)->where('admin_type', 'Followup_Admin')->where('status', 'Active')->orderBy('name', 'asc')->get();
        $groupMemberCount = DB::table('members')->where('group_id', $_groupId)->count();


        //ATTENDANCE PENDING
        $groupPendingAttendanceResults = $this->get_pending_attendance(" AND a1.sabha_id = $_sabhaId AND a1.member_id IN(SELECT id FROM `members` WHERE `group_id` = $_groupId)");
        //REGULAR
        $groupRegularResults = $this->get_regular(" AND a1.sabha_id = $_sabhaId AND a1.member_id IN(SELECT id FROM `members` WHERE `group_id` = $_groupId)");
        //FRESH
        $groupFreshResults = $this->get_fresh(" AND a1.sabha_id = $_sabhaId AND a1.member_id IN(SELECT id FROM `members` WHERE `group_id` = $_groupId)");

        //BIRTHDAY
        $todayBirthday = $this->get_birthday("AND sabha_id = $_sabhaId AND group_id = $_groupId", "today");
        $tomorrowBirthday = $this->get_birthday("AND sabha_id = $_sabhaId AND group_id = $_groupId", "tomorrow");
        $thisWeekBirthday = $this->get_birthday("AND sabha_id = $_sabhaId AND group_id = $_groupId", "this-week");
        $thisMonthBirthday = $this->get_birthday("AND sabha_id = $_sabhaId AND group_id = $_groupId", "this-month");

        //NEW JOINEE
        $weeklyNewJoinee = $this->get_newJoinee("AND sabha_id = $_sabhaId AND group_id = $_groupId", "weekly");
        $monthlyNewJoinee = $this->get_newJoinee("AND sabha_id = $_sabhaId AND group_id = $_groupId", "monthly");
        $quartelyNewJoinee = $this->get_newJoinee("AND sabha_id = $_sabhaId AND group_id = $_groupId", "quartely");
        $yearlyNewJoinee = $this->get_newJoinee("AND sabha_id = $_sabhaId AND group_id = $_groupId", "yearly");


        //Group statistics
        $dashboardData['totalFollowUpKaryakarta'] = count($followUpKaryakarta);
        $dashboardData['pendingAttendance'] = count($groupPendingAttendanceResults);
        //Youth Summary
        $dashboardData['youthSummary']['total'] = $groupMemberCount;
        $dashboardData['youthSummary']['regular'] =  count($groupRegularResults);
        $dashboardData['youthSummary']['irregular'] = ($groupMemberCount - count($groupRegularResults));
        $dashboardData['youthSummary']['fresh'] = count($groupFreshResults);
        //Birthday
        $dashboardData['todayBirthday'] = count($todayBirthday);
        $dashboardData['tomorrowBirthday'] = count($tomorrowBirthday);
        $dashboardData['thisWeekBirthday'] = count($thisWeekBirthday);
        $dashboardData['thisMonthBirthday'] = count($thisMonthBirthday);
        //New joinee
        $dashboardData['weeklyNewJoinee'] = $weeklyNewJoinee;
        $dashboardData['monthlyNewJoinee'] = $monthlyNewJoinee;
        $dashboardData['quartelyNewJoinee'] = $quartelyNewJoinee;
        $dashboardData['yearlyNewJoinee'] = $yearlyNewJoinee;

        return view('dashboard.group_detail')->with('data', $dashboardData)->with('request', $request);
    }

    public function followupkaryakartaList(Request $request)
    {
        $dashboardData = [];

        $_groupId = $request->id;
        $group = get_group_by('id', $_groupId);
        $sabha = get_sabha_by('id', $group->sabha_id);
        $groupName = $sabha->name . ' | ' . $group->name;

        $followUpKaryakarta = DB::table('admins')->where('group_id', $_groupId)->where('admin_type', 'Followup_Admin')->where('status', 'Active')->orderBy('name', 'asc')->get();
        foreach ($followUpKaryakarta as $item) {
            $_followUpKaryakartaId = $item->id;
            $dashboardData[$_followUpKaryakartaId] = (array) $item;

            //
            $youthCount = DB::table('members')->where('follow_up_by', $_followUpKaryakartaId)->count();
            $fkRegularResults = $this->get_regular(" AND a1.sabha_id = $group->sabha_id AND a1.member_id IN(SELECT id FROM `members` WHERE `follow_up_by` = $_followUpKaryakartaId)");
            $dashboardData[$_followUpKaryakartaId]['total_youth'] = $youthCount;
            $dashboardData[$_followUpKaryakartaId]['regular_youth'] = count($fkRegularResults);
            $dashboardData[$_followUpKaryakartaId]['irregular_youth'] = ($youthCount - count($fkRegularResults));
        }

        return view('dashboard.followupkaryakarta_list')->with('data', $dashboardData)->with('request', $request)->with('groupName', $groupName);
    }

    public function followupkaryakartaDetail(Request $request)
    {
        $dashboardData = [];

        $_followupKaryakartaId = $request->id;
        $dashboardData[$_followupKaryakartaId] = (array) get_admin_by('id', $_followupKaryakartaId);
        $_sabhaId = $dashboardData[$_followupKaryakartaId]['sabha_id'];
        $_groupId = $dashboardData[$_followupKaryakartaId]['group_id'];
        $dashboardData[$_followupKaryakartaId]['groupName'] = get_sabha_by('id', $_sabhaId)->name . ' | ' . get_group_by('id', $_groupId)->name;
        $youthCount = DB::table('members')->where('follow_up_by', $_followupKaryakartaId)->count();


        //ATTENDANCE PENDING
        $fkPendingAttendanceResults = $this->get_pending_attendance(" AND a1.sabha_id = $_sabhaId AND a1.member_id IN(SELECT id FROM `members` WHERE `follow_up_by` = $_followupKaryakartaId)");
        //REGULAR
        $fkRegularResults = $this->get_regular(" AND a1.sabha_id = $_sabhaId AND a1.member_id IN(SELECT id FROM `members` WHERE `follow_up_by` = $_followupKaryakartaId)");
        //FRESH
        $fkFreshResults = $this->get_fresh(" AND a1.sabha_id = $_sabhaId AND a1.member_id IN(SELECT id FROM `members` WHERE `follow_up_by` = $_followupKaryakartaId)");

        //BIRTHDAY
        $todayBirthday = $this->get_birthday("AND sabha_id = $_sabhaId AND follow_up_by = $_followupKaryakartaId", "today");
        $tomorrowBirthday = $this->get_birthday("AND sabha_id = $_sabhaId AND follow_up_by = $_followupKaryakartaId", "tomorrow");
        $thisWeekBirthday = $this->get_birthday("AND sabha_id = $_sabhaId AND follow_up_by = $_followupKaryakartaId", "this-week");
        $thisMonthBirthday = $this->get_birthday("AND sabha_id = $_sabhaId AND follow_up_by = $_followupKaryakartaId", "this-month");

        //NEW JOINEE
        $weeklyNewJoinee = $this->get_newJoinee("AND sabha_id = $_sabhaId AND follow_up_by = $_followupKaryakartaId", "weekly");
        $monthlyNewJoinee = $this->get_newJoinee("AND sabha_id = $_sabhaId AND follow_up_by = $_followupKaryakartaId", "monthly");
        $quartelyNewJoinee = $this->get_newJoinee("AND sabha_id = $_sabhaId AND follow_up_by = $_followupKaryakartaId", "quartely");
        $yearlyNewJoinee = $this->get_newJoinee("AND sabha_id = $_sabhaId AND follow_up_by = $_followupKaryakartaId", "yearly");

        //Group statistics
        $dashboardData['pendingAttendance'] = count($fkPendingAttendanceResults);
        //Youth Summary
        $dashboardData['youthSummary']['total'] = $youthCount;
        $dashboardData['youthSummary']['regular'] =  count($fkRegularResults);
        $dashboardData['youthSummary']['irregular'] = ($youthCount - count($fkRegularResults));
        $dashboardData['youthSummary']['fresh'] = count($fkFreshResults);
        //Birthday
        $dashboardData['todayBirthday'] = count($todayBirthday);
        $dashboardData['tomorrowBirthday'] = count($tomorrowBirthday);
        $dashboardData['thisWeekBirthday'] = count($thisWeekBirthday);
        $dashboardData['thisMonthBirthday'] = count($thisMonthBirthday);
        //New joinee
        $dashboardData['weeklyNewJoinee'] = $weeklyNewJoinee;
        $dashboardData['monthlyNewJoinee'] = $monthlyNewJoinee;
        $dashboardData['quartelyNewJoinee'] = $quartelyNewJoinee;
        $dashboardData['yearlyNewJoinee'] = $yearlyNewJoinee;

        return view('dashboard.followupkaryakarta_detail')->with('data', $dashboardData)->with('request', $request);
    }


    public function birthdayList(Request $request)
    {
        $dashboardData = [];
        $where = "";
        if ($request->for == 'pradesh') {
            $_backUrl = route('dashboard');
            $where = "AND pradesh_id = $request->id";
        } else if ($request->for == 'zone') {
            $_backUrl = route('dashboard.zone-detail', $request->id);
            $where = "AND zone_id = $request->id";
        } else if ($request->for == 'sabha') {
            $_backUrl = route('dashboard.sabha-detail', $request->id);
            $where = "AND sabha_id = $request->id";
        } else if ($request->for == 'group') {
            $_backUrl = route('dashboard.group-detail', $request->id);
            $where = "AND group_id = $request->id";
        } else if ($request->for == 'followupkk') {
            $_backUrl = route('dashboard.followupkaryakarta-detail', $request->id);
            $where = "AND follow_up_by = $request->id";
        }
        $todayBirthday = $this->get_birthday($where, "today");
        $tomorrowBirthday = $this->get_birthday($where, "tomorrow");
        $thisWeekBirthday = $this->get_birthday($where, "this-week");
        $thisMonthBirthday = $this->get_birthday($where, "this-month");

        //Birthday
        $dashboardData['todayBirthday'] = $todayBirthday;
        $dashboardData['tomorrowBirthday'] = $tomorrowBirthday;
        $dashboardData['thisWeekBirthday'] = $thisWeekBirthday;
        $dashboardData['thisMonthBirthday'] = $thisMonthBirthday;
        return view('dashboard.birthday_list')->with('data', $dashboardData)->with('request', $request)->with('_backURL', $_backUrl);
    }



    public function get_pending_attendance($where)
    {
        $query = "SELECT
                a1.attendance_id,
                (
                SELECT
                    COUNT(*)
                FROM
                    attenders AS a2
                WHERE
                    a1.attendance_id = a2.attendance_id
            ) AS totalAttender,
            (
                SELECT
                    COUNT(*)
                FROM
                    attenders AS a2
                WHERE
                    a1.attendance_id = a2.attendance_id AND a2.present = 'Yes'
            ) AS presentAttender,
            FORMAT(
                (
                    (
                    SELECT
                        COUNT(*)
                    FROM
                        attenders AS a2
                    WHERE
                        a1.attendance_id = a2.attendance_id AND a2.present = 'Yes'
                ) / (
                SELECT
                    COUNT(*)
                FROM
                    attenders AS a2
                WHERE
                    a1.attendance_id = a2.attendance_id
            ) * 100 ), 2 ) AS percentage
            FROM
                attenders AS a1
            WHERE a1.attendance_id NOT IN(SELECT `id` FROM `attendances` WHERE `status`='Cancel') AND 
                YEAR(a1.created_at) = '" . date(' Y ') . "' AND FORMAT(
                    (
                        (
                        SELECT
                            COUNT(*)
                        FROM
                            attenders AS a2
                        WHERE
                            a1.attendance_id = a2.attendance_id AND a2.present = 'Yes'
                    ) / (
                    SELECT
                        COUNT(*)
                    FROM
                        attenders AS a2
                    WHERE
                        a1.attendance_id = a2.attendance_id
                ) * 100 ), 2 ) < " . ATTENDANCE_PERCENTAGE . " " . $where . "
            GROUP BY
                a1.attendance_id";

        $results = DB::select($query);
        return $results;
    }

    public function get_regular($where)
    {
        $query = "SELECT
                a1.member_id,
                (
                SELECT
                    COUNT(*)
                FROM
                    attenders AS a2
                WHERE
                    a2.member_id = a1.member_id AND a2.present = 'Yes'
                ORDER BY
                    a2.id
                DESC
            LIMIT 4
            ) AS regularCount
            FROM
                attenders AS a1
            WHERE
                YEAR(a1.created_at) = '" . date(' Y ') . "' AND(
                SELECT
                    COUNT(*)
                FROM
                    attenders AS a2
                WHERE
                    a2.member_id = a1.member_id AND a2.present = 'Yes'
                ORDER BY
                    a2.id
                DESC
            LIMIT 4
            ) >= " . REGULAR_LIMIT . " " . $where . "
            GROUP BY
                a1.member_id";

        $results = DB::select($query);
        return $results;
    }

    public function get_fresh($where)
    {
        $query = "SELECT
                a1.member_id,
                (
                SELECT
                    COUNT(*)
                FROM
                    attenders AS a2
                WHERE
                    a2.present = 'Yes' AND a1.member_id = a2.member_id
            ) AS sabhaAttendCount
            FROM
                attenders AS a1
            WHERE
                YEAR(a1.created_at) = '" . date(' Y ') . "' AND(
                SELECT
                    COUNT(*)
                FROM
                    attenders AS a2
                WHERE
                    a2.present = 'Yes' AND a1.member_id = a2.member_id
            ) < " . FRESH_LIMIT . " " . $where . "
            GROUP BY
                a1.member_id";

        $results = DB::select($query);
        return $results;
    }

    public function get_birthday($where, $type)
    {
        $todayDate = Carbon::now()->format('Y-m-d');
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $query = "SELECT *, DATE_FORMAT(`date_of_birth`,'%d') AS divas, (SELECT `name` FROM `sabhas` WHERE `id`=m.sabha_id) as sabhaName, (SELECT `name` FROM `zones` WHERE `id`=m.zone_id) as zoneName FROM members as m WHERE ";
        if ($type == 'today') {
            $query .= " DATE_FORMAT(`date_of_birth`,'%m-%d') = DATE_FORMAT('" . $todayDate . "','%m-%d') $where ORDER BY first_name ASC";
        } else if ($type == 'tomorrow') {
            $query .= " DATE_FORMAT(`date_of_birth`,'%m-%d') = DATE_FORMAT('" . $tomorrow . "','%m-%d') $where ORDER BY first_name ASC";
        } else if ($type == 'this-week') {
            $startOfWeek = Carbon::now()->startOfWeek()->format('m-d');
            $endOfWeek = Carbon::now()->endOfWeek()->format('m-d');
            $query .= " DATE_FORMAT(`date_of_birth`, '%m-%d') BETWEEN '$startOfWeek' AND '$endOfWeek' $where ORDER BY divas ASC";
        } else if ($type == 'this-month') {
            $startOfMonth = Carbon::now()->startOfMonth()->format('m-d');
            $endOfMonth = Carbon::now()->endOfMonth()->format('m-d');
            $query .= " DATE_FORMAT(`date_of_birth`, '%m-%d') BETWEEN '$startOfMonth' AND '$endOfMonth' $where ORDER BY divas ASC";
        }

        $results = DB::select($query);
        return $results;
    }

    public function get_sabha($where, $type)
    {
        $query = "SELECT * FROM sabhas WHERE 1=1 ";

        $dayOfTheWeek = Carbon::now()->dayOfWeek;
        $currentDay = ($dayOfTheWeek + 1);

        if ($type == 'today') {
            $query .= " AND sabha_day = '$currentDay' ";
        } else if ($type == 'tomorrow') {
            $currentDay = $currentDay + 1;
            if ($currentDay == 8) {
                $currentDay = 1;
            }
            $query .= " AND sabha_day = '$currentDay' ";
        } else if ($type == 'this-week') {
            $query .= " AND CAST(sabha_day AS UNSIGNED) >= $currentDay ";
        }

        $query .= " AND status = 'Active' AND occurance != 'Never' $where";
        $results = DB::select($query);
        return $results;
    }

    public function get_newJoinee($where, $type)
    {
        $query = "SELECT COUNT(*) AS totalCurrentYearRegister FROM `members` WHERE DATE_FORMAT(`joining_date`, '%Y') = '" . date('Y') . "' $where";
        $results = DB::select($query);
        $currentYearRegister = $results[0]->totalCurrentYearRegister;
        $average = 0;

        //Average = Sum of Values/ Number of values
        if ($type == "weekly") {
            $average = ceil($currentYearRegister / 52);
        } else if ($type == "monthly") {
            $average = ceil($currentYearRegister / 30);
        } else if ($type == "quartely") {
            $average = ceil($currentYearRegister / 6);
        } else if ($type == "yearly") {
            $average = ceil($currentYearRegister / 12);
        }

        return $average;
    }
}
