<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $records = $this->getGroupData($request);

            return DataTables::of($records)
                ->addColumn('name', function (Group $group) {
                    return '<b>' . $group->name . '</b>';
                })
                ->addColumn('sabha_name', function (Group $group) {
                    $objSabha = get_sabha_by('id', $group->sabha_id);
                    if ($objSabha) {
                        return $objSabha->name;
                    }
                })
                ->addColumn('zone_name', function (Group $group) {
                    $objZone = get_zone_by('id', $group->zone_id);
                    if ($objZone) {
                        return $objZone->name;
                    }
                })
                ->addColumn('group_admin', function (Group $group) {
                    return get_group_admin_name($group->id);
                })
                ->addColumn('status', function (Group $group) {
                    return get_status_badge($group->status);
                })
                ->addColumn('created_by', function (Group $group) {
                    return get_created_by_name($group->created_by);
                })
                ->addColumn('action', function (Group $group) {
                    ob_start();
?>
                <?php if (current_user_can('edit', 'groups', $group->id) === true) { ?>
                    <a class="mr-2" data-toggle="modal" data-target="#modal-group-edit" data-remote="<?php echo route("groups.edit", $group->id); ?>" href="javascript:void;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>
                <?php } ?>

                <a class="mr-2" data-toggle="modal" data-target="#modal-group-view" data-remote="<?php echo route("groups.show", $group->id); ?>" href="javascript:void;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>

                <?php if (current_user_can('delete', 'groups', $group->id) === true) { ?>
                    <button type="submit" class="text-danger confirm-delete btn p-0" data-action="<?php echo route("groups.destroy", $group->id); ?>">
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
                ->rawColumns(['name', 'status', 'created_by', 'group_admin', 'action'])
                ->make(true);
        }
        return view('group.index');
    }

    public function getGroupData($request)
    {

        $records = Group::select('*');

        if (get_current_admin_level() == 'Super_Admin') :
            $records->where('id', '!=', 0);

        elseif (get_current_admin_level() == 'Country_Admin') :
            $records->where('country_id', Auth::user()->country_id);

        elseif (get_current_admin_level() == 'State_Admin') :
            $records->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id);

        elseif (get_current_admin_level() == 'Pradesh_Admin') :
            $records->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id);

        elseif (get_current_admin_level() == 'Zone_Admin') :
            $records->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id);

        elseif (get_current_admin_level() == 'Sabha_Admin') :
            $records->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id)->where('sabha_id', Auth::user()->sabha_id);

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
    public function create()
    {
        return view('group.create');
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
            'name' => ['required', 'string', 'max:150'],
        ];
        if (isset($request->sabha_id)) {
            $objSabha = get_sabha_by('id', $request->sabha_id);
        }

        if (in_array(get_current_admin_level(), ['Super_Admin', 'Country_Admin', 'State_Admin', 'Pradesh_Admin', 'Zone_Admin'])) :
            $args['sabha_id'] = ['required'];
        else :
            $objSabha = get_sabha_by('id', Auth::user()->sabha_id);
        endif;

        //Sanitization
        $inputs['name'] = strip_tags($request->name);

        //Validation
        $validator = Validator::make($inputs, $args);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()]);
        }


        $inputs['sabha_id'] = $objSabha->id;
        $inputs['pradesh_id'] = $objSabha->pradesh_id;
        $inputs['zone_id'] = $objSabha->zone_id;
        $inputs['country_id'] = $objSabha->country_id;
        $inputs['state_id'] = $objSabha->state_id;
        $inputs['city_id'] = $objSabha->city_id;

        $inputs['created_by'] = Auth::user()->id;

        $carbonDateTime = Carbon::now();
        $inputs['created_at'] = $carbonDateTime->toDateTimeString();
        $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
        $group = Group::create($inputs);

        add_admin_activity_logs("<b>{$group->name}</b> name group has been added in to the <b>{$objSabha->name}</b> sabha", "Group", "Add Group", $group->id);
        return response()->json(['success' => 1, 'errors' => []]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        $data = [];
        return view('group.view', compact('group'))->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $data = [];
        $objSabha = get_sabha_by('id', $group->sabha_id);
        if ($objSabha) {
            $data['sabha_name'] = $objSabha->name;
        }
        return view('group.edit', compact('group'))->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $inputs = $request->all();
        $args = [
            'name' => ['required', 'string', 'max:150'],
        ];
        if (isset($request->sabha_id)) {
            $objSabha = get_sabha_by('id', $request->sabha_id);
        }

        if (in_array(get_current_admin_level(), ['Super_Admin', 'Country_Admin', 'State_Admin', 'Pradesh_Admin', 'Zone_Admin'])) :
            $args['sabha_id'] = ['required'];
        else :
            $objSabha = get_sabha_by('id', Auth::user()->sabha_id);
        endif;

        //Sanitization
        $inputs['name'] = strip_tags($request->name);

        //Validation
        $validator = Validator::make($inputs, $args);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()]);
        }


        $inputs['sabha_id'] = $objSabha->id;
        $inputs['pradesh_id'] = $objSabha->pradesh_id;
        $inputs['zone_id'] = $objSabha->zone_id;
        $inputs['country_id'] = $objSabha->country_id;
        $inputs['state_id'] = $objSabha->state_id;
        $inputs['city_id'] = $objSabha->city_id;

        $carbonDateTime = Carbon::now();
        $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
        $group->update($inputs);

        add_admin_activity_logs("A group named <b>{$group->name}</b> has been updated which belongs to the group <b>{$objSabha->name}</b> sabha", "Group", "Edit Group", $group->id);
        return response()->json(['success' => 1, 'errors' => []]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->delete(); //main table
        //Admin::where('group_id', $group->id)->update(array('group_id' => NULL));
        Admin::where('group_id', $group->id)->whereIn('admin_type', ['Followup_Admin', 'Group_Admin'])->delete();
        Member::where('group_id', $group->id)->update(array('group_id' => 0, 'follow_up_by' => 0));

        $objSabha = get_sabha_by('id', $group->sabha_id);
        add_admin_activity_logs("A group named <b>{$group->name}</b> has been deleted which belongs to the group <b>{$objSabha->name}</b> sabha", "Group", "Delete Group", $group->id);

        return response()->json(['success' => 1, 'errors' => []]);
    }

    public function ajaxAutocompleteSearch(Request $request)
    {
        $groups = [];
        $fiterByLevel = '1 = 1';
        if (get_current_admin_level() == 'Super_Admin') :
            $fiterByLevel = '1 = 1';
        elseif (get_current_admin_level() == 'Country_Admin') :
            $fiterByLevel = 'g.country_id = ' . Auth::user()->country_id;
        elseif (get_current_admin_level() == 'State_Admin') :
            $fiterByLevel = 'g.state_id = ' . Auth::user()->state_id;
        elseif (get_current_admin_level() == 'Pradesh_Admin') :
            $fiterByLevel = 'g.pradesh_id = ' . Auth::user()->pradesh_id;
        elseif (get_current_admin_level() == 'Zone_Admin') :
            $fiterByLevel = 'g.zone_id = ' . Auth::user()->zone_id;
        elseif (get_current_admin_level() == 'Sabha_Admin') :
            $fiterByLevel = 'g.sabha_id = ' . Auth::user()->sabha_id;
        endif;

        $search = "";
        if ($request->has('q')) {
            $search = "AND g.name LIKE '$request->q%'";
        }
        $where = (isset($request->whereSql) && trim($request->whereSql) != '' ? $request->whereSql : '');

        $query = "SELECT g.id, CONCAT(g.name, ' | ',(SELECT name FROM sabhas WHERE id = g.sabha_id)) as name FROM groups as g WHERE $fiterByLevel AND g.status='Active' $search $where order by g.name";
        $groups = DB::select($query);

        return response()->json($groups);
    }
}
