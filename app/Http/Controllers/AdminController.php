<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\generalMail;
use App\Models\Member;
use App\Rules\nameRule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class AdminController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            if (get_current_admin_level() == 'Super_Admin') :
                $model = Admin::query()->where('id', '!=', Auth::user()->id)->where('admin_type', '!=', 'Super_Admin')->orderBy('id', 'DESC');

            elseif (get_current_admin_level() == 'Country_Admin') :
                $model = Admin::query()->where('country_id', Auth::user()->country_id)->where('id', '!=', Auth::user()->id)->where('admin_type', '!=', 'Country_Admin')->where('admin_type', '!=', 'Super_Admin')->orderBy('id', 'DESC');

            elseif (get_current_admin_level() == 'State_Admin') :
                $model = Admin::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('id', '!=', Auth::user()->id)->where('admin_type', '!=', 'State_Admin')->where('admin_type', '!=', 'Super_Admin')->orderBy('id', 'DESC');

            elseif (get_current_admin_level() == 'Pradesh_Admin') :
                $model = Admin::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('id', '!=', Auth::user()->id)->where('admin_type', '!=', 'State_Admin')->where('admin_type', '!=', 'Pradesh_Admin')->where('admin_type', '!=', 'Super_Admin')->orderBy('id', 'DESC');

            elseif (get_current_admin_level() == 'Zone_Admin') :
                $model = Admin::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id)->where('id', '!=', Auth::user()->id)->where('admin_type', '!=', 'State_Admin')->where('admin_type', '!=', 'Pradesh_Admin')->where('admin_type', '!=', 'Zone_Admin')->where('admin_type', '!=', 'Super_Admin')->orderBy('id', 'DESC');

            elseif (get_current_admin_level() == 'Sabha_Admin') :
                $model = Admin::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id)->where('sabha_id', Auth::user()->sabha_id)->where('id', '!=', Auth::user()->id)->where('admin_type', '!=', 'State_Admin')->where('admin_type', '!=', 'Pradesh_Admin')->where('admin_type', '!=', 'Zone_Admin')->where('admin_type', '!=', 'Sabha_Admin')->where('admin_type', '!=', 'Super_Admin')->orderBy('id', 'DESC');

            elseif (get_current_admin_level() == 'Group_Admin') :
                $model = Admin::query()->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id)->where('sabha_id', Auth::user()->sabha_id)->where('group_id', Auth::user()->group_id)->where('id', '!=', Auth::user()->id)->where('admin_type', '!=', 'State_Admin')->where('admin_type', '!=', 'Pradesh_Admin')->where('admin_type', '!=', 'Zone_Admin')->where('admin_type', '!=', 'Sabha_Admin')->where('admin_type', '!=', 'Group_Admin')->where('admin_type', '!=', 'Super_Admin')->orderBy('id', 'DESC');

            endif;


            return DataTables::eloquent($model)
                ->addColumn('name', function (Admin $admin) {
                    return '<strong>' . $admin->name . '</strong>';
                })
                ->addColumn('mobile_number', function (Admin $admin) {
                    return '<a target="_blank" href="tel:' . $admin->mobile_number . '">' . $admin->mobile_number . '</a>';
                })
                ->addColumn('email', function (Admin $admin) {
                    return '<a target="_blank" href="mailto:' . $admin->email . '">' . $admin->email . '</a>';
                })
                ->addColumn('admin_type', function (Admin $admin) {
                    return get_admin_type_badge($admin->admin_type);
                })
                ->addColumn('status', function (Admin $admin) {
                    return get_status_badge($admin->status);
                })
                ->addColumn('created_by', function (Admin $admin) {
                    return get_created_by_name($admin->created_by);
                })
                ->addColumn('sabha', function (Admin $admin) {
                    $sabha = get_sabha_by('id', $admin->sabha_id);
                    if ($sabha) {
                        return $sabha->name;
                    }
                })
                ->addColumn('zone', function (Admin $admin) {
                    $zone = get_zone_by('id', $admin->zone_id);
                    if ($zone) {
                        return $zone->name;
                    }
                })
                ->addColumn('group', function (Admin $admin) {
                    $group = get_group_by('id', $admin->group_id);
                    if ($group) {
                        return $group->name;
                    }
                })
                ->addColumn('action', function (Admin $admin) {
                    ob_start();
?>
                <?php if (current_user_can('edit', 'admins', $admin->id) === true) { ?>
                    <a class="mr-2" data-toggle="modal" data-target="#modal-admin-edit" data-remote="<?php echo route("admins.edit", $admin->id); ?>" href="javascript:void;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>
                <?php } ?>

                <?php if (current_user_can('delete', 'admins', $admin->id) === true) { ?>
                    <button type="submit" class="text-danger confirm-delete btn p-0" data-action="<?php echo route("admins.destroy", $admin->id); ?>">
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
                //->orderColumn('name', 'name $1')
                ->filterColumn('name', function ($query, $keyword) {
                    $sql = "name like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->rawColumns(['name', 'mobile_number', 'email',  'admin_type', 'status', 'created_by', 'state', 'action'])
                ->make(true);
        }
        return view('admin.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create');
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

        //$password = get_random_password();
        $name = strip_tags($request->name);
        $nameArray = explode(" ", trim($name));
        $fname = (isset($nameArray[0]) ? trim($nameArray[0]) : 'hpy');
        $password = $fname . '@369';
        $inputs['password'] = Hash::make($password);

        // Validation
        $args = [
            'admin_type' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, get_admin_type_list_by_level())) {
                        $fail("The :attribute invalid field value.");
                    }
                }
            ]
        ];
        $args['country_id'] = ['required_if:admin_type,==,Country_Admin'];
        $args['state_id'] = ['required_if:admin_type,==,State_Admin'];
        $args['pradesh_id'] = ['required_if:admin_type,==,Pradesh_Admin'];
        $args['zone_id'] = ['required_if:admin_type,==,Zone_Admin'];
        $args['sabha_id'] = ['required_if:admin_type,==,Sabha_Admin', 'required_if:admin_type,==,Group_Admin', 'required_if:admin_type,==,Followup_Admin'];
        $args['group_id'] = ['required_if:admin_type,==,Group_Admin', 'required_if:admin_type,==,Followup_Admin'];
        $args['name'] = ['required', 'string', 'max:255', new nameRule];
        $args['email'] = ['required', 'string', 'email', 'max:255', 'unique:admins'];
        //$args['mobile_number'] = ['required', 'digits:10', 'max:11', 'unique:admins'];
        $args['mobile_number'] = ['required', 'digits:10', 'max:11'];

        //Sanitization
        $inputs['name'] = strip_tags($request->name);
        if (get_current_admin_level() == 'Sabha_Admin') {
            $inputs['sabha_id'] = Auth::user()->sabha_id;
        }


        //Validation
        $validator = Validator::make($inputs, $args);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()]);
        }

        $email_mes = 'Super Admin';
        if ($inputs['admin_type'] == 'State_Admin') {
            $objState = get_state_by('id', $inputs['state_id']);
            $inputs['country_id'] = $objState->country_id;
            $email_mes = 'State_Admin of ' . $objState->name . ' by ' . Auth::user()->name;
        } elseif ($inputs['admin_type'] == 'Pradesh_Admin') {
            $objPradesh = get_pradesh_by('id', $inputs['pradesh_id']);
            $inputs['country_id'] = $objPradesh->country_id;
            $inputs['state_id'] = $objPradesh->state_id;
            $email_mes = 'Pradesh_Admin of ' . $objPradesh->name . ' by ' . Auth::user()->name;
        } elseif ($inputs['admin_type'] == 'Zone_Admin') {
            $objZone = get_zone_by('id', $inputs['zone_id']);
            $inputs['country_id'] = $objZone->country_id;
            $inputs['state_id'] = $objZone->state_id;
            $inputs['pradesh_id'] = $objZone->pradesh_id;
            $email_mes = 'Zone_Admin of ' . $objZone->name . ' by ' . Auth::user()->name;
        } elseif ($inputs['admin_type'] == 'Sabha_Admin') {
            $objSabha = get_sabha_by('id', $inputs['sabha_id']);
            $inputs['country_id'] = $objSabha->country_id;
            $inputs['state_id'] = $objSabha->state_id;
            $inputs['pradesh_id'] = $objSabha->pradesh_id;
            $inputs['zone_id'] = $objSabha->zone_id;
            $email_mes = 'Sabha_Admin of ' . $objSabha->name . ' by ' . Auth::user()->name;
        } elseif ($inputs['admin_type'] == 'Group_Admin') {
            $objGroup = get_group_by('id', $inputs['group_id']);
            $inputs['country_id'] = $objGroup->country_id;
            $inputs['state_id'] = $objGroup->state_id;
            $inputs['pradesh_id'] = $objGroup->pradesh_id;
            $inputs['zone_id'] = $objGroup->zone_id;
            $inputs['sabha_id'] = $objGroup->sabha_id;
            $email_mes = 'Group_Admin of ' . $objGroup->name . ' group by ' . Auth::user()->name;
        } elseif ($inputs['admin_type'] == 'Followup_Admin') {
            if (Auth::user()->sabha_id != NULL) {
                $objSabha = get_sabha_by('id', Auth::user()->sabha_id);
            } else {
                $objSabha = get_sabha_by('id', $inputs['sabha_id']);
            }
            $inputs['admin_type'] = 'Followup_Admin';
            $inputs['country_id'] = $objSabha->country_id;
            $inputs['state_id'] = $objSabha->state_id;
            $inputs['pradesh_id'] = $objSabha->pradesh_id;
            $inputs['zone_id'] = $objSabha->zone_id;
            $inputs['sabha_id'] = $objSabha->id;
            $email_mes = 'Followup_Admin of ' . $objSabha->name . ' by ' . Auth::user()->name;
        }

        $inputs['created_by'] = Auth::user()->id;

        $carbonDateTime = Carbon::now();
        $inputs['created_at'] = $carbonDateTime->toDateTimeString();
        $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
        $admin = Admin::create($inputs);


        add_admin_activity_logs("<b>{$admin->name}</b> has been added as a <b>{$inputs['admin_type']}</b> admin", "Admin", "Add Admin", $admin->id);

        // Send email
        $mailData = [
            'subject' => 'Login access - ' . config('app.name'),
            'view' => 'emails.general',
            'body' => '<p
                style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
                Jai Swaminarayan,<br>
                Das na das,<br>
                ' . $inputs['name'] . '.<br><br>
                You have been added as ' . $email_mes . '. Your login details are below.<br><br>
                <strong>Link:</strong> ' . route('dashboard') . '<br>
                <strong>Email:</strong> ' . $inputs['email'] . '<br>
                <strong>Password:</strong> ' . $password . '<br><br><br>
                if any questions let ' . Auth::user()->name . ' know.
            </p>'
        ];
        try {
            Mail::to($inputs['email'])->send(new generalMail($mailData));
        } catch (Exception $ex) {
        }

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
    public function edit(Admin $admin)
    {
        $data = [];

        $data['group_name'] = '';
        if ($admin->group_id) {
            $objGroup = get_group_by('id', $admin->group_id);
            $data['group_name'] = $objGroup->name;
        }

        $data['sabha_name'] = '';
        $objSabha = get_sabha_by('id', $admin->sabha_id);
        if ($objSabha) {
            $data['sabha_name'] = $objSabha->name;
        }

        $data['zone_name'] = '';
        $objZone = get_zone_by('id', $admin->zone_id);
        if ($objZone) {
            $data['zone_name'] = $objZone->name;
        }

        return view('admin.edit', compact('admin'))->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $inputs = $request->all();

        // Validation
        $args = [
            'admin_type' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, get_admin_type_list_by_level())) {
                        $fail("The :attribute invalid field value.");
                    }
                }
            ]
        ];
        $args['country_id'] = ['required_if:admin_type,==,Country_Admin'];
        $args['state_id'] = ['required_if:admin_type,==,State_Admin'];
        $args['pradesh_id'] = ['required_if:admin_type,==,Pradesh_Admin'];
        $args['zone_id'] = ['required_if:admin_type,==,Zone_Admin'];
        $args['sabha_id'] = ['required_if:admin_type,==,Sabha_Admin', 'required_if:admin_type,==,Group_Admin', 'required_if:admin_type,==,Followup_Admin'];
        $args['group_id'] = ['required_if:admin_type,==,Group_Admin', 'required_if:admin_type,==,Followup_Admin'];
        $args['name'] = ['required', 'string', 'max:255', new nameRule];
        $args['email'] = ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . $admin->id];
        //$args['mobile_number'] = ['required', 'digits:10', 'unique:admins,mobile_number,' . $admin->id];
        $args['mobile_number'] = ['required', 'digits:10', 'max:11'];

        if (isset($inputs['isChangePassword']) && $inputs['isChangePassword'] == 'Yes') {
            $args['password'] = [
                'required', 'min:8',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            ];
            $inputs['password'] = Hash::make($inputs['password']);
        } else {
            unset($inputs['password']);
        }

        //Sanitization
        $inputs['name'] = strip_tags($request->name);
        if (get_current_admin_level() == 'Sabha_Admin') {
            $inputs['sabha_id'] = Auth::user()->sabha_id;
        }

        $validator = Validator::make($inputs, $args);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()]);
        }

        if ($inputs['admin_type'] == 'State_Admin') {
            $objState = get_state_by('id', $inputs['state_id']);
            $inputs['country_id'] = $objState->country_id;
        } elseif ($inputs['admin_type'] == 'Pradesh_Admin') {
            $objPradesh = get_pradesh_by('id', $inputs['pradesh_id']);
            $inputs['country_id'] = $objPradesh->country_id;
            $inputs['state_id'] = $objPradesh->state_id;
        } elseif ($inputs['admin_type'] == 'Zone_Admin') {
            $objZone = get_zone_by('id', $inputs['zone_id']);
            $inputs['country_id'] = $objZone->country_id;
            $inputs['state_id'] = $objZone->state_id;
            $inputs['pradesh_id'] = $objZone->pradesh_id;
        } elseif ($inputs['admin_type'] == 'Sabha_Admin') {
            $objSabha = get_sabha_by('id', $inputs['sabha_id']);
            $inputs['country_id'] = $objSabha->country_id;
            $inputs['state_id'] = $objSabha->state_id;
            $inputs['pradesh_id'] = $objSabha->pradesh_id;
            $inputs['zone_id'] = $objSabha->zone_id;
        } elseif ($inputs['admin_type'] == 'Group_Admin') {
            $objGroup = get_group_by('id', $inputs['group_id']);
            $inputs['country_id'] = $objGroup->country_id;
            $inputs['state_id'] = $objGroup->state_id;
            $inputs['pradesh_id'] = $objGroup->pradesh_id;
            $inputs['zone_id'] = $objGroup->zone_id;
            $inputs['sabha_id'] = $objGroup->sabha_id;
        } elseif ($inputs['admin_type'] == 'Followup_Admin') {
            if (Auth::user()->sabha_id != NULL) {
                $objSabha = get_sabha_by('id', Auth::user()->sabha_id);
            } else {
                $objSabha = get_sabha_by('id', $inputs['sabha_id']);
            }
            $inputs['admin_type'] = 'Followup_Admin';
            $inputs['country_id'] = $objSabha->country_id;
            $inputs['state_id'] = $objSabha->state_id;
            $inputs['pradesh_id'] = $objSabha->pradesh_id;
            $inputs['zone_id'] = $objSabha->zone_id;
            $inputs['sabha_id'] = $objSabha->id;
        }

        $carbonDateTime = Carbon::now();
        $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
        $admin->update($inputs);


        add_admin_activity_logs("<b>{$admin->name}</b> has been updated who is the <b>{$admin->admin_type}</b> admin", "Admin", "Edit Admin", $admin->id);

        return response()->json(['success' => 1, 'errors' => []]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        add_admin_activity_logs("<b>{$admin->name}</b> has been deleted from <b>{$admin->admin_type}</b> admin", "Admin", "Delete Admin", $admin->id);
        return response()->json(['success' => 1, 'errors' => []]);
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        $inputs = $request->all();
        $admin = Auth::user();

        $args = [];
        $args['name'] = ['required', 'string', 'max:255', new nameRule];
        //$args['mobile_number'] = ['required', 'digits:10', 'unique:admins,id,' . $admin->id];
        $args['mobile_number'] = ['required', 'digits:10', 'max:11'];
        if (isset($request->isChangePassword) && $request->isChangePassword == 'Yes') {
            $args['password'] = [
                'required', 'min:8',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            ];
            $admin->password = Hash::make($request->password);
        }
        $validator = Validator::make($request->all(), $args);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()]);
        }

        $admin->name = $request->name;
        $admin->mobile_number = $request->mobile_number;
        $admin->save();
        add_admin_activity_logs("The <b>{$admin->name}</b> profile has been updated who is the <b>{$admin->admin_type}</b> admin", "Admin", "Edit Admin Profile", $admin->id);
        return response()->json(['success' => 1, 'errors' => []]);
    }

    public function ajaxAutocompleteSearch(Request $request)
    {
        $admins = [];

        $fiterByLevel = '1 = 1';
        if (get_current_admin_level() == 'Super_Admin') :
            $fiterByLevel = '1 = 1';
        elseif (get_current_admin_level() == 'Country_Admin') :
            $fiterByLevel = 'a.country_id = ' . Auth::user()->country_id;
        elseif (get_current_admin_level() == 'State_Admin') :
            $fiterByLevel = 'a.state_id = ' . Auth::user()->state_id;
        elseif (get_current_admin_level() == 'Pradesh_Admin') :
            $fiterByLevel = 'a.pradesh_id = ' . Auth::user()->pradesh_id;
        elseif (get_current_admin_level() == 'Zone_Admin') :
            $fiterByLevel = 'a.zone_id = ' . Auth::user()->zone_id;
        elseif (get_current_admin_level() == 'Sabha_Admin') :
            $fiterByLevel = 'a.sabha_id = ' . Auth::user()->sabha_id;
        elseif (get_current_admin_level() == 'Group_Admin') :
            $fiterByLevel = 'a.sabha_id = ' . Auth::user()->sabha_id;
        endif;

        $search = "";
        if ($request->has('q')) {
            $search = " and a.name LIKE '%$request->q%'";
        }
        $where = (isset($request->whereSql) && trim($request->whereSql) != '' ? $request->whereSql : '');

        $query = "SELECT a.id, CONCAT(a.name,' | ',(SELECT name FROM sabhas WHERE id = a.sabha_id),' | ',(SELECT name FROM zones WHERE id = a.zone_id)) as name FROM admins as a WHERE $fiterByLevel and admin_type != 'Super_Admin' and a.status = 'Active' $search $where order by a.name";
        $admins = DB::select($query);

        return response()->json($admins);
    }
}
