<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    //
    public function attendanceReport(Request $request)
    {
        if ($request->ajax()) {

            $reportResult = [];

            $is_filter = false;
            $where_attendances = $memnerOn = $attendersAnd = "";

            //filterNoOfSabha
            if ($request->filterNoOfSabha) {
                $is_filter = true;
            }

            //filterZoneId
            if ($request->filterZone_id) {
                $where_attendances .= " AND zone_id = $request->filterZone_id";
                $is_filter = true;
            }

            //filterSabha_id
            if ($request->filterSabha_id) {
                $where_attendances .= " AND sabha_id = $request->filterSabha_id";
                $is_filter = true;
            }
            if (in_array(get_current_admin_level(), ['Sabha_Admin'])) {
                $where_attendances .= " AND sabha_id = '" . Auth::user()->sabha_id . "'";
                $is_filter = true;
            }

            //filterPresence
            if ($request->filterPresence) {
                $attendersAnd .= " AND attenders.present = '$request->filterPresence'";
                $is_filter = true;
            }

            //Date
            if ($request->filterFormDate != '' && $request->filterToDate != '') {
                $dateFrom = date('Ymd', strtotime($request->filterFormDate));
                $dateTo = date('Ymd', strtotime($request->filterToDate));

                $where_attendances .= " AND DATE_FORMAT(sabha_date, '%Y%m%d') >= '$dateFrom' AND DATE_FORMAT(sabha_date, '%Y%m%d') <= '$dateTo'";
                $is_filter = true;
            }

            //filterGroup_id
            if ($request->filterGroup_id) {
                $memnerOn .= " AND members.group_id = $request->filterGroup_id";
                $is_filter = true;
            }

            //filterFollowup_id
            if ($request->filterFollowup_id) {
                $memnerOn .= " AND members.follow_up_by = $request->filterFollowup_id";
                $is_filter = true;
            }

            if ($is_filter === true) {
                $query = "SELECT
                    (SELECT `name` FROM `zones` WHERE zones.id = attenders.zone_id) AS zone_name,
                    (SELECT `name` FROM `sabhas` WHERE sabhas.id = attenders.sabha_id) AS sabha_name,
                    (SELECT `sabha_date` FROM `attendances` AS a2 WHERE a2.id = attendances.id) AS sabha_date,
                    (SELECT `name` FROM `groups` WHERE groups.id = members.group_id) AS group_name,
                    (SELECT `name` FROM `admins` WHERE admins.id = members.follow_up_by) AS followup,
                    attenders.present,
                    members.first_name,
                    CONCAT(members.`first_name`, ' ', members.`middle_name`, ' ', members.`surname`) AS member_name,
                    CONCAT(members.`flat_no`, ' ', members.`building_name`, ' ', members.`landmark`, ' ', members.`street_name`, ' ', members.`postcode`) AS member_address,
                    members.email,
                    members.mobile,
                    members.gender,
                    members.date_of_birth,
                    members.marital_status,
                    members.anniversery_date,
                    members.member_is,
                    members.attending_sabha,
                    members.joining_date,
                    members.ref_name,
                    members.avd_id,
                    members.ambrish_code,
                    members.performing_puja,
                    members.nishtawan,
                    members.reference_id,
                    members.id as member_id
                FROM
                    attenders
                INNER JOIN(SELECT id FROM `attendances` WHERE 1=1 $where_attendances) AS attendances ON attenders.attendance_id = attendances.id $attendersAnd
                INNER JOIN members ON members.id = attenders.member_id $memnerOn
                ORDER BY sabha_date DESC, members.first_name ASC";
                $reportResult = DB::select($query);
            }

            $reportData = [];
            foreach ($reportResult as $row) {
                // $lastAbsence = get_member_attendance_last_absence_count($row->member_id);
                // //if check last number of sabha absence
                // if ($request->filterNoOfSabha != "") {
                //     if ($request->filterConditions == "<") {
                //         if ($lastAbsence < $request->filterNoOfSabha) {
                //         }else{
                //             continue;
                //         }
                //     }
                //     if ($request->filterConditions == "<=") {
                //         if ($lastAbsence <= $request->filterNoOfSabha) {
                //         }else{
                //             continue;
                //         }
                //     }
                //     if ($request->filterConditions == ">") {
                //         if ($lastAbsence > $request->filterNoOfSabha) {
                //         }else{
                //             continue;
                //         }
                //     }
                //     if ($request->filterConditions == ">=") {
                //         if ($lastAbsence >= $request->filterNoOfSabha) {
                //         }else{
                //             continue;
                //         }
                //     }
                // }

                $sabha_date = Carbon::parse($row->sabha_date)->format('j F, Y');
                $date_of_birth = Carbon::parse($row->date_of_birth)->format('j F, Y');

                $joining_date = NULL;
                if ($row->joining_date != NULL && $row->joining_date != '0000-00-00') {
                    $joining_date = Carbon::parse($row->joining_date)->format('j F, Y');
                }

                $ref_name = NULL;
                if ($row->reference_id == 0) {
                    $ref_name = $row->ref_name;
                } else {
                    $ref_name = get_member_fullname($row->reference_id);
                }

                //create row
                $array = [
                    $sabha_date,
                    $row->zone_name,
                    $row->sabha_name,
                    $row->group_name,
                    $row->followup,
                    $row->present,
                    //$lastAbsence,
                    '<strong>' . $row->member_name . '</strong>',
                    get_member_type_badge($row->member_is),
                    $row->email,
                    $row->mobile,
                    $row->member_address,
                    $row->gender,
                    $date_of_birth,
                    $row->marital_status,
                    $row->attending_sabha,
                    $joining_date,
                    $ref_name,
                    $row->avd_id,
                    $row->ambrish_code,
                    $row->performing_puja,
                    $row->nishtawan
                ];
                //push array
                $reportData[] = $array;
            }

            print json_encode([
                "draw" => $request->draw,
                "recordsTotal" => count($reportData),
                "recordsFiltered" => count($reportData),
                "data" => $reportData
            ], true);
            exit;
        }

        return view('reports.attendance');
    }
}
