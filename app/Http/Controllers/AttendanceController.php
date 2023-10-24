<?php

namespace App\Http\Controllers;

use App\Models\Attendances;
use App\Models\Attenders;
use App\Models\Sabha;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $records = Attendances::select('*');

            if (get_current_admin_level() == 'Super_Admin') :
                $records->withCount('attenders_data')->where('id', '!=', 0);

            elseif (get_current_admin_level() == 'Country_Admin') :
                $records->withCount('attenders_data')->where('country_id', Auth::user()->country_id);

            elseif (get_current_admin_level() == 'State_Admin') :
                $records->withCount('attenders_data')->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id);

            elseif (get_current_admin_level() == 'Pradesh_Admin') :
                $records->withCount('attenders_data')->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id);

            elseif (get_current_admin_level() == 'Zone_Admin') :
                $records->withCount('attenders_data')->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id);

            elseif (get_current_admin_level() == 'Sabha_Admin') :
                $records->withCount('attenders_data')->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id)->where('sabha_id', Auth::user()->sabha_id);

            elseif (get_current_admin_level() == 'Group_Admin') :
                $records->withCount('attenders_data')->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id)->where('sabha_id', Auth::user()->sabha_id);

            elseif (get_current_admin_level() == 'Followup_Admin') :
                $records->withCount('attenders_data')->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id)->where('sabha_id', Auth::user()->sabha_id);

            endif;

            //Datatable Search
            if ($request->search['value']) {
                $keyword = $request->search['value'];
                $records->whereIn('sabha_id', Sabha::where('name', 'LIKE', "{$keyword}%")->pluck('id')->toArray());
            }

            //search by zone
            if ($request->filterZone_id) {
                $records->where('zone_id', $request->filterZone_id);
            }
            //search by sabha
            if ($request->filterSabha_id) {
                $records->where('sabha_id', $request->filterSabha_id);
            }
            //search by Status
            if ($request->filterStatus) {
                $records->where('status', $request->filterStatus);
            }
            //search by Status
            if ($request->filterSabhaDate) {
                $records->whereDate('sabha_date', date("Y-m-d", strtotime($request->filterSabhaDate)));
            }

            $records->orderby('sabha_date', 'DESC');

            return DataTables::of($records)
                ->addColumn('sabha_name', function (Attendances $attendance) {
                    $objSabha = get_sabha_by('id', $attendance->sabha_id);
                    if ($objSabha) {
                        return '<b>' . $objSabha->name . '</b>';
                    }
                    return '';
                })
                ->addColumn('zone_name', function (Attendances $attendance) {
                    $objZone = get_zone_by('id', $attendance->zone_id);
                    if ($objZone) {
                        return $objZone->name;
                    }
                })
                ->addColumn('sabha_date', function (Attendances $attendance) {
                    $d = Carbon::parse($attendance->sabha_date)->format('j F, Y');
                    return $d;
                })
                ->addColumn('total_member', function (Attendances $attendance) {
                    return $attendance->attenders_data_count;
                })
                ->addColumn('present_member', function (Attendances $attendance) {
                    return get_attendance_present_count($attendance->id);
                })
                ->addColumn('absence_member', function (Attendances $attendance) {
                    return get_attendance_absence_count($attendance->id);
                })
                ->addColumn('status', function (Attendances $attendance) {
                    return get_attendance_status_badge($attendance->status);
                })
                ->addColumn('percentage', function (Attendances $attendance) {
                    return get_calculate_attendance_percentage($attendance->id) . '%';
                })
                ->addColumn('1vakta', function (Attendances $attendance) {
                    return get_member_fullname($attendance->vakta1);
                })
                ->addColumn('1topic', function (Attendances $attendance) {
                    return $attendance->vakta1_topic;
                })
                ->addColumn('2vakta', function (Attendances $attendance) {
                    return get_member_fullname($attendance->vakta2);
                })
                ->addColumn('2topic', function (Attendances $attendance) {
                    return $attendance->vakta2_topic;
                })
                ->addColumn('reason', function (Attendances $attendance) {
                    return $attendance->reason;
                })
                ->addColumn('action', function (Attendances $attendance) {
                    ob_start();
?>
                <?php if (current_user_can('edit', 'attendances', $attendance->id) === true && $attendance->status != 'Cancel') { ?>
                    <a class="mr-2" href="<?php echo route("attendances.edit", $attendance->id); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                        </svg>
                    </a>
                <?php } ?>

                <?php if (current_user_can('add_edit_vakta', 'attendances', $attendance->id) === true && $attendance->status != 'Cancel') { ?>
                    <a class="mr-2" data-toggle="modal" data-target="#modal-add-edit-vakta" data-remote="<?php echo route("attendances.vaktaAddEdit", $attendance->id); ?>" href="javascript:void;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" />
                        </svg>
                    </a>
                <?php } ?>

                <?php if (current_user_can('delete', 'attendances', $attendance->id) === true) { ?>
                    <button type="submit" class="mr-2 text-danger confirm-delete btn p-0" data-action="<?php echo route("attendances.destroy", $attendance->id); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                    </button>
                <?php } ?>

                <?php if (current_user_can('cancel_sabha', 'attendances', $attendance->id) === true && $attendance->status == 'Pending') { ?>
                    <button type="submit" class="text-danger cancel_sabha btn p-0" data-sabha="<?php echo $attendance->id ?>" data-action="<?php echo route("attendances.sabhaCancel"); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                <?php } ?>
<?php
                    $output = ob_get_contents();
                    ob_end_clean();

                    return $output;
                })
                ->rawColumns(['sabha_name', 'status', 'vakta', 'action'])
                ->make(true);
        }

        return view('attendance.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendances $attendance)
    {
        $records = Attenders::select('attenders.*', 'members.first_name', 'members.middle_name', 'members.surname', 'members.mobile', 'members.group_id', 'members.follow_up_by');
        $records->where('attendance_id', '=', $attendance->id);

        if (get_current_admin_level() == 'Followup_Admin') {
            $records->whereIn('member_id', get_my_followup_memner_ids());
        } else if (get_current_admin_level() == 'Group_Admin') {
            $records->whereIn('member_id',  get_my_memner_ids_by_group(Auth::user()->group_id));
        }

        $records->join('members', 'attenders.member_id', '=', 'members.id');
        $records->orderby('members.first_name', 'asc');
        $attender = $records->get();

        $data['attenders'] = $attender;
        $sabha_date = Carbon::parse($attendance->sabha_date)->format('j F, Y');
        $data['sabha_date'] = $sabha_date;
        return view('attendance.edit', compact('attendance'), $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendances $attendance)
    {
        $inputs = $request->all();

        try {
            if (count($inputs['attdmem'])) {
                foreach ($inputs['attdmem'] as $member_id) {
                    DB::table('attenders')
                        ->where('attendance_id', $attendance->id)
                        ->where('member_id', $member_id)
                        ->update(['present' => 'Yes']);
                }
            }
            if (get_calculate_attendance_percentage($attendance->id) > ATTENDANCE_PERCENTAGE) {
                $inputs['status'] = 'Completed';
            }

            $carbonDateTime = Carbon::now();
            $inputs['updated_at'] = $carbonDateTime->toDateTimeString();

            $attendance->update($inputs);

            $objSabha = get_sabha_by('id', $attendance->sabha_id);
            add_admin_activity_logs("<b>{$objSabha->name}</b> sabha attendance has been taken", "Attendance", "Take Attendance", $attendance->id);
        } catch (\Throwable $th) {
            throw $th;
        }
        return response()->json(['success' => 1, 'errors' => []]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendances $attendance)
    {
        $attendance->delete(); //main table
        $attender = Attenders::whereIn('attendance_id', [$attendance->id])->delete(); //child table

        $objSabha = get_sabha_by('id', $attendance->sabha_id);
        add_admin_activity_logs("<b>{$objSabha->name}</b> attendance has been deleted", "Attendance", "Delete Attendance", $attendance->id);

        return response()->json(['success' => 1, 'errors' => []]);
    }
    public function sabhaCancel(Request $request)
    {
        $inputs = $request->all();
        $attendance = Attendances::find($inputs['sabha']);
        $attendance->update([
            'status' => 'Cancel',
            'reason' => $inputs['reason']
        ]);

        $objSabha = get_sabha_by('id', $attendance->sabha_id);
        add_admin_activity_logs("<b>{$objSabha->name}</b> Sabha Canecelation Submitted", "Attendance", "Cancel Sabha", $attendance->id);

        return response()->json(['success' => 1, 'errors' => []]);
    }


    public function vaktaAddEdit(Request $request)
    {
        $data = [];
        $attendance = Attendances::find($request->id);
        return view('attendance.vakta', compact('attendance'))->with('data', $data);
    }

    public function vaktaStoreUpdate(Request $request)
    {
        $inputs = $request->all();
        $attendance = Attendances::find($inputs['attendance_id']);
        if (get_calculate_attendance_percentage($attendance->id) > ATTENDANCE_PERCENTAGE) {
            $inputs['status'] = 'Completed';
        }
        $attendance->update($inputs);

        $objSabha = get_sabha_by('id', $attendance->sabha_id);
        add_admin_activity_logs("<b>{$objSabha->name}</b> assembly speaker has been added", "Attendance", "Add Speaker", $attendance->id);

        return response()->json(['success' => 1, 'errors' => []]);
    }

    public function attendanceSingleMember(Request $request)
    {
        $inputs = $request->all();

        $attender = Attenders::find($inputs['id']);
        if ($attender->present == 'Yes') {
            $inputs['present'] = 'No';
        } else {
            $inputs['present'] = 'Yes';
        }

        $inputs['attendance_by'] = Auth::user()->id;
        $attender->update($inputs);

        $TotalCount = $PresentCount = $AbsentCount = 0;
        $query = "SELECT 
        (SELECT COUNT(*) FROM `attenders` WHERE attendance_id = $attender->attendance_id) as TotalCount, 
        (SELECT COUNT(*) FROM `attenders` WHERE attendance_id = $attender->attendance_id AND present='Yes') as PresentCount, 
        (SELECT COUNT(*) FROM `attenders` WHERE attendance_id = $attender->attendance_id AND present='No') as AbsentCount 
        FROM `attenders` WHERE attendance_id = $attender->attendance_id group by attendance_id";
        $results = DB::select($query);
        $TotalCount = $results[0]->TotalCount;
        $PresentCount =  $results[0]->PresentCount;
        $AbsentCount =  $results[0]->AbsentCount;

        return response()->json(['success' => 1, 'errors' => [], 'TotalCount' => $TotalCount, 'PresentCount' => $PresentCount, 'AbsentCount' => $AbsentCount]);
    }
}
