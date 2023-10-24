<?php

namespace App\Http\Controllers;

use App\Models\TagsMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TagMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $records = TagsMaster::select('*', DB::raw("(SELECT count(*) FROM `member_tags` where member_tags.tag_id = tags_master.id) as usedCount"))->orderby('id', 'desc');

            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('name', function (TagsMaster $tagsMaster) {
                    return '<b>' . $tagsMaster->name . '</b>';
                })
                ->addColumn('created_by', function (TagsMaster $tagsMaster) {
                    return get_created_by_name($tagsMaster->created_by);
                })
                ->addColumn('action', function (TagsMaster $tagsMaster) {
                    ob_start();
?>
                <?php if (current_user_can('edit', 'tagsMaster', $tagsMaster->id) === true) { ?>
                    <a class="mr-2" data-toggle="modal" data-target="#modal-tags-edit" data-remote="<?php echo route("tagsMaster.edit", $tagsMaster->id); ?>" href="javascript:void;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>
                <?php } ?>

                <?php if (current_user_can('delete', 'tagsMaster', $tagsMaster->id) === true) { ?>
                    <button type="submit" class="text-danger confirm-delete btn p-0" data-action="<?php echo route("tagsMaster.destroy", $tagsMaster->id); ?>">
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
                ->rawColumns(['name', 'action', 'created_by'])
                ->make(true);
        }
        return view('tag_master.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('tag_master.create');
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
        $inputs = $request->all();
        $args = [
            'name' => ['required', 'string', 'max:50', 'unique:tags_master'],
        ];
        if (isset($request->sabha_id)) {
            $objSabha = get_sabha_by('id', $request->sabha_id);
        }

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
        $tagsMaster = TagsMaster::create($inputs);

        add_admin_activity_logs("<b>{$tagsMaster->name}</b> name tag has been added", "TagsMaster", "Add Tag", $tagsMaster->id);
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
    public function edit(TagsMaster $tagsMaster)
    {
        //
        return view('tag_master.edit', compact('tagsMaster'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TagsMaster $tagsMaster)
    {
        //
        $inputs = $request->all();
        $args = [
            'name' => ['required', 'string', 'max:50', 'unique:tags_master,name,' . $tagsMaster->id],
        ];

        //Sanitization
        $inputs['name'] = strip_tags($request->name);

        //Validation
        $validator = Validator::make($inputs, $args);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()]);
        }

        $carbonDateTime = Carbon::now();
        $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
        $tagsMaster->update($inputs);

        add_admin_activity_logs("A tag named <b>{$tagsMaster->name}</b> has been updated", "TagsMaster", "Edit tag", $tagsMaster->id);
        return response()->json(['success' => 1, 'errors' => []]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TagsMaster $tagsMaster)
    {
        //
        add_admin_activity_logs("A tag named <b>{$tagsMaster->name}</b> has been deleted", "TagsMaster", "Delete Tag", $tagsMaster->id);

        $tagsMaster->delete();
        return response()->json(['success' => 1, 'errors' => []]);
    }

    public function ajaxAutocompleteSearch(Request $request)
    {
        $tags = [];

        $search = "1=1";
        if ($request->has('q')) {
            $search = "`name` LIKE '$search%'";
        }
        $where = (isset($request->whereSql) && trim($request->whereSql) != '' ? $request->whereSql : '');

        $query = "SELECT * FROM `tags_master` WHERE $search $where ORDER BY name ASC";
        $tags = DB::select($query);
        
        return response()->json($tags);
    }
}
