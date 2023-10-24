<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\Attenders;
use App\Models\Attendances;
use App\Models\Cron_logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CronjobController extends Controller
{
    //
    public function createSabhaAttendance(Request $request)
    {
        $dayOfTheWeek = Carbon::now()->dayOfWeek;
        $weekOfMonth = Carbon::now()->weekOfMonth;
        $currentDay = ($dayOfTheWeek + 1);

        $dt = Carbon::now();

        $sql = "SELECT * FROM `sabhas` WHERE `sabha_day` = '{$currentDay}' AND `status` = 'Active' AND `occurance` != 'Never' AND id NOT IN(SELECT `sabha_id` FROM `attendances` WHERE `sabha_date` = '" . $dt->format('Y-m-d') . "') ";
        $todaySabhaList = DB::select($sql);

        Log::info("Cron is working fine! " . Carbon::now());

        foreach ($todaySabhaList as $row) {
            if ($row->occurance == 'Every 1 Week') {
                $this->saveSabhaAttendanceData($row);
            }

            if ($row->occurance == 'Every 2 Week' && ($weekOfMonth == 1 || $weekOfMonth == 3 || $weekOfMonth == 5)) {
                $this->saveSabhaAttendanceData($row);
            }
        }

        echo "Sabha Attendance has been created...";
        dd();
    }
    public function saveSabhaAttendanceData($row)
    {
        $todayDate = Carbon::now()->format('Y-m-d');

        $carbonDateTime = Carbon::now();

        DB::insert("INSERT INTO `attendances`(`id`, `country_id`, `state_id`, `city_id`, `pradesh_id`, `zone_id`, `sabha_id`, `sabha_date`, `status`, `created_at`, `updated_at`) VALUES (NULL, $row->country_id, $row->state_id, $row->city_id, $row->pradesh_id, $row->zone_id, $row->id, '{$todayDate}', 'Pending', '" . $carbonDateTime->toDateTimeString() . "', '" . $carbonDateTime->toDateTimeString() . "')");
        $attendance_id = DB::getPdo()->lastInsertId();

        $sabhaMemberList = DB::select("SELECT * FROM `members` WHERE `sabha_id` = '{$row->id}' AND `attending_sabha` = 'Yes'");
        foreach ($sabhaMemberList as $member) {
            DB::insert("INSERT INTO `attenders`(`id`, `country_id`, `state_id`, `city_id`, `pradesh_id`, `zone_id`, `sabha_id`, `attendance_id`, `member_id`, `present`, `attendance_by`, `created_at`, `updated_at`) VALUES (NULL, $row->country_id, $row->state_id, $row->city_id, $row->pradesh_id, $row->zone_id, $row->id, $attendance_id, $member->id, 'No','0','" . $carbonDateTime->toDateTimeString() . "','" . $carbonDateTime->toDateTimeString() . "')");
        }
    }



    //update joinin date based on attend first sabha
    public function joiningDate()
    {
        $withoutJoiningDateMember = Member::whereNull('joining_date')->orWhere('joining_date', '=', '0000-00-00')->pluck('id')->toArray();
        // $totalUpdates = 0;
        // $notUpdatedMembers = [];
        foreach ($withoutJoiningDateMember as $memberId) {
            // Find the first 'Attenders' record for the member.
            $attender = Attenders::where('member_id', $memberId)->first();
            if ($attender) {
                // Get the 'attendance_id' from the 'Attenders' record.
                $attendanceId = $attender->attendance_id;
                // Find the 'Attendances' record with the matching 'attendance_id'.
                $attendance = Attendances::find($attendanceId);
                if ($attendance) {
                    // Update the joining date for the member.
                    $member = Member::find($memberId);
                    $member->joining_date = $attendance->sabha_date;
                    $member->save();

                    /*
                    // Increment the total updates counter.
                    $totalUpdates++;
                    $member_data = get_member_by('id',$memberId);
                    $description = $member_data->first_name . ' ' . $member_data->surname . "'s joining date has been updated to " . $member_data->joining_date . '. User contact is :' . $member_data->mobile;
                    $data_add = Cron_logs::create([
                        'description' => $description,
                        'cron_type' => "Joining date",
                        'status' => "Success",
                        'created_at' => now()
                    ]);
                    */
                }
            } else {
                /*
                $member = Member::find($memberId);
                $member_data = get_member_by('id', $memberId);
                $notUpdatedMembers[] = $memberId;
                Cron_logs::create([
                    'description' => $member_data->first_name . ' ' . $member_data->surname . "'s joining date update failed because they did not attend any sabha. User contact is " . $member_data->mobile,
                    'cron_type' => "Joining date",
                    'status' => "Fail",
                    'created_at' => now()
                ]);
                */
            }
        }
        /*
        // After the loop, add a final entry with the total updates count
        Cron_logs::create([
            'description' => "Total updates: $totalUpdates",
            'cron_type' => "Joining date",
            'status' => "Success", // You can adjust this based on your needs
        ]);
        */
    }



    //update member Attending Sabha Status based on last 11 sabha not attend
    public function updateMemberAttendingSabhaStatus()
    {
        $members = Member::where('attending_sabha', '=', 'Yes')->pluck('id')->toArray();
        foreach ($members as $memberId) {
            if (get_member_attendance_last_absence_count($memberId) >= 11) {
                // Update the joining date for the member.
                $member = Member::find($memberId);
                $member->attending_sabha = 'No';
                $member->save();
            }
        }
    }
}
