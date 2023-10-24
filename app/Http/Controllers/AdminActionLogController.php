<?php

namespace App\Http\Controllers;

use App\Models\AdminActionLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AdminActionLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            if (get_current_admin_level() == 'Super_Admin') :
                $model = AdminActionLog::query()->where('id', '!=', 0)->orderBy('created_at', 'DESC');

            elseif (get_current_admin_level() == 'Country_Admin') :
                $model = AdminActionLog::query()->where('country_id', Auth::user()->country_id)->orderBy('created_at', 'DESC');

            elseif (get_current_admin_level() == 'State_Admin') :
                $model = AdminActionLog::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->orderBy('created_at', 'DESC');

            elseif (get_current_admin_level() == 'Pradesh_Admin') :
                $model = AdminActionLog::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->orderBy('created_at', 'DESC');

            elseif (get_current_admin_level() == 'Zone_Admin') :
                $model = AdminActionLog::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id)->orderBy('created_at', 'DESC');

            elseif (get_current_admin_level() == 'Sabha_Admin') :
                $model = AdminActionLog::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id)->where('sabha_id', Auth::user()->sabha_id)->orderBy('created_at', 'DESC');

            endif;


            return DataTables::eloquent($model)
                ->addColumn('action_by', function (AdminActionLog $activityLogs) {
                    return get_created_by_name($activityLogs->admin_id, true);
                })
                ->addColumn('ip_address', function (AdminActionLog $activityLogs) {
                    return ''; //$activityLogs->ip_address;
                })
                ->addColumn('action_type', function (AdminActionLog $activityLogs) {
                    return $activityLogs->action_type;
                })
                ->addColumn('action_description', function (AdminActionLog $activityLogs) {
                    return $activityLogs->action_description;
                })
                ->addColumn('action_date', function (AdminActionLog $activityLogs) {
                    $d = Carbon::parse($activityLogs->created_at)->format('j F, Y @ h:i a');
                    return str_replace('@', 'at', $d);
                })
                ->filterColumn('action_description', function ($query, $keyword) {
                    $query->orWhere('action_description', 'like', "%$keyword%")->orWhere('action_type', 'like', "%$keyword%")->orWhere('module_name', 'like', "%$keyword%");
                })
                ->rawColumns([
                    "action_by",
                    "action_description",
                ])
                ->make(true);
        }
        return view('admin_action_log.index');
    }
}
