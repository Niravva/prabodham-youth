<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $records = $this->getZoneData($request);

            return DataTables::of($records)
                ->addColumn('name', function (Zone $zone) {
                    return '<b>' . $zone->name . '</b>';
                })
                ->addColumn('number_of_sabha', function (Zone $zone) {
                    return $zone->sabha_data_count;
                })
                ->addColumn('state_name', function (Zone $zone) {
                    $objState = get_state_by('id', $zone->state_id);
                    if ($objState) {
                        return $objState->name;
                    }
                })
                ->addColumn('pradesh_name', function (Zone $zone) {
                    $objPradesh = get_pradesh_by('id', $zone->pradesh_id);
                    if ($objPradesh) {
                        return $objPradesh->name;
                    }
                })
                ->addColumn('status', function (Zone $zone) {
                    return get_status_badge($zone->status);
                })
                ->addColumn('created_by', function (Zone $zone) {
                    return get_created_by_name($zone->created_by);
                })
                ->addColumn('action', function (Zone $zone) {
                    ob_start();
?>
                <?php if (current_user_can('edit', 'zones', $zone->id) === true) { ?>
                    <a class="mr-2" data-toggle="modal" data-target="#modal-zone-edit" data-remote="<?php echo route("zones.edit", $zone->id); ?>" href="javascript:void;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>
                <?php } ?>

                <?php if (current_user_can('delete', 'zones', $zone->id) === true) { ?>
                    <button type="submit" class="text-danger confirm-delete btn p-0" data-action="<?php echo route("zones.destroy", $zone->id); ?>">
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
                ->rawColumns(['name', 'status', 'created_by', 'action'])
                ->make(true);
        }
        return view('zone.index');
    }

    public function getZoneData($request){

        $records = Zone::select('*');

        if (get_current_admin_level() == 'Super_Admin') :
            $records->withCount('sabha_data')->where('id', '!=', 0);

        elseif (get_current_admin_level() == 'Country_Admin') :
            $records->withCount('sabha_data')->where('country_id', Auth::user()->country_id);

        elseif (get_current_admin_level() == 'State_Admin') :
            $records->withCount('sabha_data')->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id);

        elseif (get_current_admin_level() == 'Pradesh_Admin') :
            $records->withCount('sabha_data')->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id);

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
        return view('zone.create');
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
            'name' => ['required'],
        ];
        if (isset($request->pradesh_id)) {
            $objPradesh = get_pradesh_by('id', $request->pradesh_id);
        }

        if (in_array(get_current_admin_level(), ['Super_Admin', 'Country_Admin', 'State_Admin'])) :
            $args['pradesh_id'] = ['required'];
        else :
            $objPradesh = get_pradesh_by('id', Auth::user()->pradesh_id);
        endif;


        //Sanitization
        $inputs['name'] = strip_tags($request->name);

        //Validation
        $validator = Validator::make($inputs, $args);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()]);
        }


        $inputs['pradesh_id'] = $objPradesh->id;
        $inputs['country_id'] = $objPradesh->country_id;
        $inputs['state_id'] = $objPradesh->state_id;
        $inputs['city_id'] = $objPradesh->city_id;

        $inputs['created_by'] = Auth::user()->id;

        $carbonDateTime = Carbon::now();
        $inputs['created_at'] = $carbonDateTime->toDateTimeString();
        $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
        $zone = Zone::create($inputs);

        add_admin_activity_logs("<b>{$zone->name}</b> zone has been added", "Zone", "Add Zone", $zone->id);

        return response()->json(['success' => 1, 'errors' => []]);
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
    public function edit(Request $request, Zone $zone)
    {
        return view('zone.edit', compact('zone'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zone $zone)
    {
        $inputs = $request->all();
        $args = [
            'name' => ['required'],
        ];
        if (isset($request->pradesh_id)) {
            $objPradesh = get_pradesh_by('id', $request->pradesh_id);
        }

        if (in_array(get_current_admin_level(), ['Super_Admin', 'Country_Admin', 'State_Admin'])) :
            $args['pradesh_id'] = ['required'];
        else :
            $objPradesh = get_pradesh_by('id', Auth::user()->pradesh_id);
        endif;


        //Sanitization
        $inputs['name'] = strip_tags($request->name);

        //Validation
        $validator = Validator::make($inputs, $args);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()]);
        }


        $inputs['pradesh_id'] = $objPradesh->id;
        $inputs['country_id'] = $objPradesh->country_id;
        $inputs['state_id'] = $objPradesh->state_id;
        $inputs['city_id'] = $objPradesh->city_id;

        $carbonDateTime = Carbon::now();
        $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
        $zone->update($inputs);

        add_admin_activity_logs("<b>{$zone->name}</b> zone has been updated", "Zone", "Edit Zone", $zone->id);

        return response()->json(['success' => 1, 'errors' => []]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Zone $zone)
    {
        $zone->delete();

        add_admin_activity_logs("<b>{$zone->name}</b> zone has been deleted", "Zone", "Delete Zone", $zone->id);

        return response()->json(['success' => 1, 'errors' => []]);
    }

    public function ajaxAutocompleteSearch(Request $request)
    {
        $zones = [];

        $fiterByLevel = '1 = 1';
        if (get_current_admin_level() == 'Super_Admin') :
            $fiterByLevel = '1 = 1';
        elseif (get_current_admin_level() == 'Country_Admin') :
            $fiterByLevel = 'z.country_id = ' . Auth::user()->country_id;
        elseif (get_current_admin_level() == 'State_Admin') :
            $fiterByLevel = 'z.state_id = ' . Auth::user()->state_id;
        elseif (get_current_admin_level() == 'Pradesh_Admin') :
            $fiterByLevel = 'z.pradesh_id = ' . Auth::user()->pradesh_id;
        endif;

        $search = "";
        if ($request->has('q')) {
            $search = "and z.name LIKE '$request->q%'";
        }
        $where = (isset($request->whereSql) && trim($request->whereSql) != '' ? $request->whereSql : '');

        $query = "SELECT z.id, CONCAT(z.name, ' | ',(SELECT name FROM pradeshs WHERE id = z.pradesh_id)) as name FROM zones as z WHERE $fiterByLevel and z.status = 'Active' $search  $where order by z.name";
        $zones = DB::select($query);

        return response()->json($zones);
    }
}
