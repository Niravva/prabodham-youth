<?php

namespace App\Http\Controllers;

use App\Models\Sabha;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SabhaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $records = $this->getSabhaData($request);

            return DataTables::of($records)
                ->addColumn('name', function (Sabha $sabha) {
                    return '<b>' . $sabha->name . '</b>';
                })
                ->addColumn('code', function (Sabha $sabha) {
                    return $sabha->sabha_code;
                })
                ->addColumn('zone_name', function (Sabha $sabha) {
                    $objZone = get_zone_by('id', $sabha->zone_id);
                    if ($objZone) {
                        return $objZone->name;
                    }
                })
                ->addColumn('sabha_head', function (Sabha $sabha) {
                    return get_member_fullname($sabha->sabha_head_id, true);
                })
                ->addColumn('sabha_type', function (Sabha $sabha) {
                    return $sabha->sabha_type;
                })
                ->addColumn('number_of_member', function (Sabha $sabha) {
                    return $sabha->members_data_count;
                })
                ->addColumn('day', function (Sabha $sabha) {
                    return get_sabha_days()[$sabha->sabha_day];
                })
                ->addColumn('time', function (Sabha $sabha) {
                    return $sabha->sabha_time;
                })
                ->addColumn('status', function (Sabha $sabha) {
                    return get_status_badge($sabha->status);
                })
                ->addColumn('flat_no', function (Sabha $sabha) {
                    return $sabha->flat_no;
                })
                ->addColumn('building_name', function (Sabha $sabha) {
                    return $sabha->building_name;
                })
                ->addColumn('landmark', function (Sabha $sabha) {
                    return $sabha->landmark;
                })
                ->addColumn('street_name', function (Sabha $sabha) {
                    return $sabha->street_name;
                })
                ->addColumn('postcode', function (Sabha $sabha) {
                    return $sabha->postcode;
                })
                ->addColumn('created_by', function (Sabha $sabha) {
                    return get_created_by_name($sabha->created_by);
                })
                ->addColumn('action', function (Sabha $sabha) {
                    ob_start();
?>
                <?php if (current_user_can('edit', 'sabhas', $sabha->id) === true) { ?>
                    <a class="mr-2" data-toggle="modal" data-target="#modal-sabha-edit" data-remote="<?php echo route("sabhas.edit", $sabha->id); ?>" href="javascript:void;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>
                <?php } ?>

                <a class="mr-2" data-toggle="modal" data-target="#modal-sabha-view" data-remote="<?php echo route("sabhas.show", $sabha->id); ?>" href="javascript:void;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>

                <?php if (current_user_can('delete', 'sabhas', $sabha->id) === true) { ?>
                    <button type="submit" class="text-danger confirm-delete btn p-0" data-action="<?php echo route("sabhas.destroy", $sabha->id); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                    </button>
                <?php } ?>
<?php
                    $output = ob_get_contents();
                    ob_end_clean();

                    return $output;
                })
                ->rawColumns(['name', 'sabha_head', 'status', 'created_by', 'action'])
                ->make(true);
        }
        return view('sabha.index');
    }


    //
    public function getSabhaData($request)
    {
        $records = Sabha::select('*');

        if (get_current_admin_level() == 'Super_Admin') :
            $records->withCount('members_data')->where('id', '!=', 0);

        elseif (get_current_admin_level() == 'Country_Admin') :
            $records->withCount('members_data')->where('country_id', Auth::user()->country_id);

        elseif (get_current_admin_level() == 'State_Admin') :
            $records->withCount('members_data')->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id);

        elseif (get_current_admin_level() == 'Pradesh_Admin') :
            $records->withCount('members_data')->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id);

        elseif (get_current_admin_level() == 'Zone_Admin') :
            $records->withCount('members_data')->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id);
        endif;

        //Datatable Search
        if ($request->search['value']) {
            $keyword = $request->search['value'];
            $records->where(function ($query) use ($keyword) {
                $query->orWhere('name', 'like', "$keyword%")->orWhere('name', 'like', "%$keyword");
            });
        }

        //Orderby name
        if ($request->order[0]['column']) {
            $records->orderBy('name', $request->order[0]['dir']);
        } else {
            $records->orderby('name', 'asc');
        }

        return $records;
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('sabha.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $args = [
            'sabha_code' => ['required', 'string', 'max:20', 'unique:sabhas'],
            'name' => ['required'],
            'sabha_type' => ['required'],
            'occurance' => ['required'],
            'sabha_day' => ['required'],
            'sabha_hour' => ['required'],
            'sabha_minute' => ['required'],
            //'flat_no' => ['required'],
            //'building_name' => ['required'],
            //'landmark' => ['required'],
            //'street_name' => ['required'],
            //'postcode' => ['required']
        ];
        if ($inputs['latitude'] != '') {
            $args['latitude'] = ['regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'];
        }
        if ($inputs['longitude'] != '') {
            $args['longitude'] = ['regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'];
        }
        if (isset($request->zone_id)) {
            $objZone = get_zone_by('id', $request->zone_id);
        }

        if (in_array(get_current_admin_level(), ['Super_Admin', 'Country_Admin', 'State_Admin', 'Pradesh_Admin'])) :
            $args['zone_id'] = ['required'];
        else :
            $objZone = get_zone_by('id', Auth::user()->zone_id);
        endif;


        //Sanitization
        $inputs['name'] = strip_tags($request->name);
        $inputs['sabha_code'] = strip_tags($request->sabha_code);
        $inputs['flat_no'] = strip_tags($request->flat_no);
        $inputs['building_name'] = strip_tags($request->building_name);
        $inputs['landmark'] = strip_tags($request->landmark);
        $inputs['street_name'] = strip_tags($request->street_name);
        $inputs['postcode'] = strip_tags($request->postcode);

        //Validation
        $validator = Validator::make($inputs, $args);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()]);
        }

        $inputs['sabha_time'] = $request->sabha_hour . ':' . $request->sabha_minute;
        $inputs['zone_id'] = $objZone->id;
        $inputs['pradesh_id'] = $objZone->pradesh_id;
        $inputs['country_id'] = $objZone->country_id;
        $inputs['state_id'] = $objZone->state_id;
        $inputs['city_id'] = $objZone->city_id;

        $inputs['created_by'] = Auth::user()->id;

        $carbonDateTime = Carbon::now();
        $inputs['created_at'] = $carbonDateTime->toDateTimeString();
        $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
        $sabha = Sabha::create($inputs);

        add_admin_activity_logs("<b>{$sabha->name}</b> sabha has been added", "Sabha", "Add Sabha", $sabha->id);

        return response()->json(['success' => 1, 'errors' => []]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Sabha $sabha)
    {
        $data['pradesh'] = get_pradesh_by('id', $sabha->pradesh_id);
        $data['zone'] = get_zone_by('id', $sabha->zone_id);
        $data['country'] = get_country_by('id', $sabha->country_id);
        $data['state'] = get_state_by('id', $sabha->state_id);
        $data['city'] = get_city_by('id', $sabha->city_id);

        return view('sabha.view', compact('sabha'), $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Sabha $sabha)
    {
        $timeArray = explode(':', $sabha->sabha_time);
        $data['sabha_hour'] = $timeArray[0];
        $data['sabha_minute'] = $timeArray[1];

        $data['zone_name'] = '';
        $objZone = get_zone_by('id', $sabha->zone_id);
        if ($objZone) {
            $data['zone_name'] = $objZone->name;
        }

        return view('sabha.edit', compact('sabha'), $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sabha $sabha)
    {
        $inputs = $request->all();
        $args = [
            'sabha_code' => ['required', 'string', 'max:20', 'unique:sabhas,sabha_code,' . $sabha->id],
            'name' => ['required'],
            'sabha_type' => ['required'],
            'occurance' => ['required'],
            'sabha_day' => ['required'],
            'sabha_hour' => ['required'],
            'sabha_minute' => ['required'],
            //'flat_no' => ['required'],
            //'building_name' => ['required'],
            //'landmark' => ['required'],
            //'street_name' => ['required'],
            //'postcode' => ['required']
        ];
        if ($inputs['latitude'] != '') {
            $args['latitude'] = ['regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'];
        }
        if ($inputs['longitude'] != '') {
            $args['longitude'] = ['regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'];
        }
        if (isset($request->zone_id)) {
            $objZone = get_zone_by('id', $request->zone_id);
        }

        if (in_array(get_current_admin_level(), ['Super_Admin', 'Country_Admin', 'State_Admin', 'Pradesh_Admin'])) :
            $args['zone_id'] = ['required'];
        else :
            $objZone = get_zone_by('id', Auth::user()->zone_id);
        endif;


        //Sanitization
        $inputs['name'] = strip_tags($request->name);
        $inputs['sabha_code'] = strip_tags($request->sabha_code);
        $inputs['flat_no'] = strip_tags($request->flat_no);
        $inputs['building_name'] = strip_tags($request->building_name);
        $inputs['landmark'] = strip_tags($request->landmark);
        $inputs['street_name'] = strip_tags($request->street_name);
        $inputs['postcode'] = strip_tags($request->postcode);

        //Validation
        $validator = Validator::make($inputs, $args);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()]);
        }

        $inputs['sabha_time'] = $request->sabha_hour . ':' . $request->sabha_minute;
        $inputs['zone_id'] = $objZone->id;
        $inputs['pradesh_id'] = $objZone->pradesh_id;
        $inputs['country_id'] = $objZone->country_id;
        $inputs['state_id'] = $objZone->state_id;
        $inputs['city_id'] = $objZone->city_id;


        $carbonDateTime = Carbon::now();
        $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
        $sabha->update($inputs);

        add_admin_activity_logs("<b>{$sabha->name}</b> sabha has been updated", "Sabha", "Edit Sabha", $sabha->id);

        return response()->json(['success' => 1, 'errors' => []]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sabha $sabha)
    {
        $sabha->delete();

        add_admin_activity_logs("<b>{$sabha->name}</b> sabha has been deleted", "Sabha", "Delete Sabha", $sabha->id);

        return response()->json(['success' => 1, 'errors' => []]);
    }

    public function ajaxAutocompleteSearch(Request $request)
    {
        $sabhas = [];

        $fiterByLevel = '1 = 1';
        if (get_current_admin_level() == 'Super_Admin') :
            $fiterByLevel = '1 = 1';
        elseif (get_current_admin_level() == 'Country_Admin') :
            $fiterByLevel = 's.country_id = ' . Auth::user()->country_id;
        elseif (get_current_admin_level() == 'State_Admin') :
            $fiterByLevel = 's.state_id = ' . Auth::user()->state_id;
        elseif (get_current_admin_level() == 'Pradesh_Admin') :
            $fiterByLevel = 's.pradesh_id = ' . Auth::user()->pradesh_id;
        elseif (get_current_admin_level() == 'Zone_Admin') :
            $fiterByLevel = 's.zone_id = ' . Auth::user()->zone_id;
        endif;
        
        $search = "";
        if ($request->has('q')) {
            $search = "and s.name LIKE '$request->q%'";
        }
        $where = (isset($request->whereSql) && trim($request->whereSql) != '' ? $request->whereSql : '');

        $query = "SELECT s.id, CONCAT(s.name,' | ',(SELECT name FROM zones WHERE id = s.zone_id),' | ',(SELECT name FROM pradeshs WHERE id = s.pradesh_id)) as name FROM sabhas as s WHERE $fiterByLevel and s.status = 'Active' $search $where order by s.name";
        $sabhas = DB::select($query);

        return response()->json($sabhas);
    }
}
