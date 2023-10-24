<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cron_logs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CronLogsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            if (get_current_admin_level() == 'Super_Admin') :
                $model = Cron_logs::query()->where('id', '!=', 0)->orderBy('created_at', 'DESC');

            elseif (get_current_admin_level() == 'Country_Admin') :
                $model = Cron_logs::query()->where('country_id', Auth::user()->country_id)->orderBy('created_at', 'DESC');

            elseif (get_current_admin_level() == 'State_Admin') :
                $model = Cron_logs::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->orderBy('created_at', 'DESC');

            elseif (get_current_admin_level() == 'Pradesh_Admin') :
                $model = Cron_logs::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->orderBy('created_at', 'DESC');

            elseif (get_current_admin_level() == 'Zone_Admin') :
                $model = Cron_logs::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id)->orderBy('created_at', 'DESC');

            elseif (get_current_admin_level() == 'Sabha_Admin') :
                $model = Cron_logs::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id)->where('sabha_id', Auth::user()->sabha_id)->orderBy('created_at', 'DESC');

            endif;

            // dd($model);

            return DataTables::eloquent($model)
                ->addColumn('description', function (Cron_logs $cron_activity_logs) {
                    return $cron_activity_logs->description;
                })
                ->addColumn('cron_type', function (Cron_logs $cron_activity_logs) {
                    return $cron_activity_logs->cron_type;
                })
                ->addColumn('status', function (Cron_logs $cron_activity_logs) {
                    return $cron_activity_logs->status;
                })
                ->addColumn('created_at', function (Cron_logs $cron_activity_logs) {
                    $d = Carbon::parse($cron_activity_logs->created_at)->format('j F, Y @ h:i a');
                    return str_replace('@', 'at', $d);
                    return $cron_activity_logs->created_at;
                })
                ->make(true);
        }
        return view('admin_cron_log.index');
    }
}
