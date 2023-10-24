<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Pradesh;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PradeshController extends Controller
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
                $model = Pradesh::query()->withCount('zone_data')->where('id', '!=', 0);

            elseif (get_current_admin_level() == 'Country_Admin') :
                $model = Pradesh::query()->withCount('zone_data')->where('country_id', Auth::user()->country_id);

            elseif (get_current_admin_level() == 'State_Admin') :
                $model = Pradesh::query()->withCount('zone_data')->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id);

            endif;


            return DataTables::eloquent($model)
                ->addColumn('name', function (Pradesh $pradesh) {
                    return '<b>' . $pradesh->name . '</b>';
                })
                ->addColumn('state_name', function (Pradesh $pradesh) {
                    $objState = get_state_by('id', $pradesh->state_id);
                    if ($objState) {
                        return $objState->name;
                    }
                })
                ->addColumn('number_of_zone', function (Pradesh $pradesh) {
                    return $pradesh->zone_data_count;
                })
                ->addColumn('status', function (Pradesh $pradesh) {
                    return get_status_badge($pradesh->status);
                })
                ->addColumn('created_by', function (Pradesh $pradesh) {
                    return get_created_by_name($pradesh->created_by);
                })
                ->addColumn('action', function (Pradesh $pradesh) {
                    ob_start();
?>
                <?php if (current_user_can('edit', 'pradeshs', $pradesh->id) === true) { ?>
                    <a class="mr-2" data-toggle="modal" data-target="#modal-pradesh-edit" data-remote="<?php echo route("pradeshs.edit", $pradesh->id); ?>" href="javascript:void;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>
                <?php } ?>

                <?php if (current_user_can('delete', 'pradeshs', $pradesh->id) === true) { ?>
                    <button type="submit" class="text-danger confirm-delete btn p-0" data-action="<?php echo route("pradeshs.destroy", $pradesh->id); ?>">
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
                ->orderColumn('name', 'name $1')
                ->filterColumn('name', function ($query, $keyword) {
                    $sql = "name like ?";
                    $query->whereRaw($sql, ["{$keyword}%"]);
                })
                ->rawColumns(['name', 'status', 'created_by', 'action'])
                ->make(true);
        }
        return view('pradesh.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->selected = 0;
        $request->return_json = 1;
        if (get_current_admin_level() == 'Super_Admin') :
            $data['country_dropdwon'] = app(CountryController::class)->index($request);

        elseif (get_current_admin_level() == 'Country_Admin') :
            $request->return_json = 0;
            $request->country_id = Auth::user()->country_id;
            $data['state_dropdwon'] = app(StateController::class)->index($request);

        elseif (get_current_admin_level() == 'State_Admin') :
            $request->return_json = 0;
            $request->state_id = Auth::user()->state_id;
            $data['city_dropdwon'] = app(CityController::class)->index($request);

        endif;

        return view('pradesh.create', $data);
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
            'name' => ['required']
        ];

        if (get_current_admin_level() == 'Super_Admin') :
            $args['country_id'] = ['required'];
            $args['state_id'] = ['required'];
            $args['city_id'] = ['required'];

        elseif (get_current_admin_level() == 'Country_Admin') :
            $inputs['country_id'] = Auth::user()->country_id;
            $args['state_id'] = ['required'];
            $args['city_id'] = ['required'];

        elseif (get_current_admin_level() == 'State_Admin') :
            $inputs['country_id'] = Auth::user()->country_id;
            $inputs['state_id'] = Auth::user()->state_id;
            $args['city_id'] = ['required'];

        endif;

        //Sanitization
        $inputs['name'] = strip_tags($request->name);

        //Validation
        $validator = Validator::make($inputs, $args);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()]);
        }

        $inputs['created_by'] = Auth::user()->id;

        $carbonDateTime = Carbon::now();
        $inputs['created_at'] = $carbonDateTime->toDateTimeString();
        $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
        $pradesh = Pradesh::create($inputs);

        add_admin_activity_logs("<b>{$pradesh->name}</b> pradesh has been added", "Pradesh", "Add Pradesh", $pradesh->id);

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
    public function edit(Request $request, Pradesh $pradesh)
    {
        $request->selected = 0;
        $request->return_json = 1;
        if (get_current_admin_level() == 'Super_Admin') :
            $request->selected = $pradesh->country_id;
            $data['country_dropdwon'] = app(CountryController::class)->index($request);

        elseif (get_current_admin_level() == 'Country_Admin') :
            $request->return_json = 0;
            $request->country_id = Auth::user()->country_id;
            $request->selected = $pradesh->state_id;
            $data['state_dropdwon'] = app(StateController::class)->index($request);

        elseif (get_current_admin_level() == 'State_Admin') :
            $request->return_json = 0;
            $request->state_id = Auth::user()->state_id;
            $request->selected = $pradesh->city_id;
            $data['city_dropdwon'] = app(CityController::class)->index($request);

        endif;

        return view('pradesh.edit', compact('pradesh'), $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pradesh $pradesh)
    {
        $inputs = $request->all();
        $args = [
            'name' => ['required']
        ];

        if (get_current_admin_level() == 'Super_Admin') :
            $args['country_id'] = ['required'];
            $args['state_id'] = ['required'];
            $args['city_id'] = ['required'];
        elseif (get_current_admin_level() == 'Country_Admin') :
            $inputs['country_id'] = Auth::user()->country_id;
            $args['state_id'] = ['required'];
            $args['city_id'] = ['required'];
        elseif (get_current_admin_level() == 'State_Admin') :
            $inputs['country_id'] = Auth::user()->country_id;
            $inputs['state_id'] = Auth::user()->state_id;
            $args['city_id'] = ['required'];
        endif;

        //Sanitization
        $inputs['name'] = strip_tags($request->name);

        //Validation
        $validator = Validator::make($inputs, $args);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()]);
        }

        $carbonDateTime = Carbon::now();
        $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
        $pradesh->update($inputs);

        add_admin_activity_logs("<b>{$pradesh->name}</b> pradesh has been updated", "Pradesh", "Edit Pradesh", $pradesh->id);

        return response()->json(['success' => 1, 'errors' => []]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pradesh $pradesh)
    {
        $pradesh->delete();

        add_admin_activity_logs("<b>{$pradesh->name}</b> pradesh has been deleted", "Pradesh", "Delete Pradesh", $pradesh->id);

        return response()->json(['success' => 1, 'errors' => []]);
    }
}
