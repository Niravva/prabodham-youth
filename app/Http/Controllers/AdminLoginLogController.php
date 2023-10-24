<?php

namespace App\Http\Controllers;

use App\Models\AdminLoginLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AdminLoginLogController extends Controller
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
                $model = AdminLoginLog::query()->where('id', '!=', 0)->where('action_type', '=', 'Login')->orderBy('created_at', 'DESC');

            elseif (get_current_admin_level() == 'Country_Admin') :
                $model = AdminLoginLog::query()->where('country_id', Auth::user()->country_id)->where('action_type', '=', 'Login')->orderBy('created_at', 'DESC');

            elseif (get_current_admin_level() == 'State_Admin') :
                $model = AdminLoginLog::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('action_type', '=', 'Login')->orderBy('created_at', 'DESC');

            elseif (get_current_admin_level() == 'Pradesh_Admin') :
                $model = AdminLoginLog::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('action_type', '=', 'Login')->orderBy('created_at', 'DESC');

            elseif (get_current_admin_level() == 'Zone_Admin') :
                $model = AdminLoginLog::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id)->where('action_type', '=', 'Login')->orderBy('created_at', 'DESC');

            elseif (get_current_admin_level() == 'Sabha_Admin') :
                $model = AdminLoginLog::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id)->where('sabha_id', Auth::user()->sabha_id)->where('action_type', '=', 'Login')->orderBy('created_at', 'DESC');

            endif;


            return DataTables::eloquent($model)
                ->addColumn('login_by', function (AdminLoginLog $loginLog) {
                    return get_created_by_name($loginLog->admin_id, true);
                })
                ->addColumn('ip_address', function (AdminLoginLog $loginLog) {
                    return ''; //$loginLog->ip_address;
                })
                ->addColumn('session_time', function (AdminLoginLog $loginLog) {
                    return '';
                })
                ->addColumn('location', function (AdminLoginLog $loginLog) {
                    $location_item = '';
                    if (!is_null($loginLog->location)) {
                        $location_array = unserialize($loginLog->location);
                        if ($location_array['city']) {
                            $location_item .= $location_array['city'];
                        }
                        if ($location_array['state_name']) {
                            $location_item .= ', ' . $location_array['state_name'];
                        }
                        if ($location_array['postal_code']) {
                            $location_item .= ', ' . $location_array['postal_code'];
                        }
                        // $location_item .= '<ul>';
                        // foreach ($location_array as $key => $item) {
                        //     $location_item .= '<li>' . $key . ': ' . $item . '</li>';
                        // }
                        // $location_item .= '</ul>';
                    }
                    return $location_item;
                })
                ->addColumn('login_at', function (AdminLoginLog $loginLog) {
                    $d = Carbon::parse($loginLog->created_at)->format('j F, Y @ h:i a');
                    return str_replace('@', 'at', $d);
                })
                ->rawColumns(["login_by", "login_at", "location"])
                ->make(true);
        }
        return view('admin_login_log.index');
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
