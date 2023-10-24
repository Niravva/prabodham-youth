<?php

namespace App\Http\Controllers;

use App\Models\Attenders;
use App\Models\Member;
use App\Models\MemberTag;
use App\Rules\nameRule;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $records = $this->getMemberData($request);

            return DataTables::of($records)
                ->addColumn('name', function (Member $member) {
                    return '<a class="mr-2" style="color: rgba(76, 78, 100, 1);" data-toggle="modal" data-target="#modal-member-view" data-remote="' . route("members.show", $member->id) . '" href="javascript:void;"><strong>' . get_member_fullname($member->id) . '</strong></a>';
                })
                ->addColumn('photo', function (Member $member) {
                    $_photoUrl = asset('assets/img/yuvak-placehoder.png');
                    if ($member->photo) {
                        $_photoUrl = url('uploads/member_photo') . '/' . $member->photo;
                    }
                    return '<img class="img-yuvak-list" src="' . $_photoUrl . '" alt="yuvak">';
                })
                ->addColumn('donoarId', function (Member $member) {
                    return $member->avd_id;
                })
                ->addColumn('mobile', function (Member $member) {
                    return '<a target="_blank" href="tel:' . $member->mobile . '">' . $member->mobile . '</a>';
                })
                ->addColumn('dob', function (Member $member) {
                    $d = Carbon::parse($member->date_of_birth)->format('jS F, Y');
                    return $d;
                })
                ->addColumn('ref_name', function (Member $member) {
                    if ($member->reference_id == 0) {
                        return $member->ref_name;
                    }
                    return get_member_fullname($member->reference_id, true);
                })
                ->addColumn('email', function (Member $member) {
                    return '<a target="_blank" href="mailto:' . $member->email . '">' . $member->email . '</a>';
                })
                ->addColumn('joining_date', function (Member $member) {
                    if ($member->joining_date != NULL && $member->joining_date != '0000-00-00') {
                        $d = Carbon::parse($member->joining_date)->format('j F, Y');
                        return $d;
                    }
                    return $member->joining_date;
                })
                ->addColumn('attending_sabha', function (Member $member) {
                    return $member->attending_sabha;
                })
                ->addColumn('created_by', function (Member $member) {
                    return get_created_by_name($member->created_by);
                })
                ->addColumn('sabha', function (Member $member) {
                    $sabha = get_sabha_by('id', $member->sabha_id);
                    if ($sabha) {
                        return $sabha->name;
                    }
                })
                ->addColumn('zone', function (Member $member) {
                    $zone = get_zone_by('id', $member->zone_id);
                    if ($zone) {
                        return $zone->name;
                    }
                })
                ->addColumn('group', function (Member $member) {
                    $group = get_group_by('id', $member->group_id);
                    if ($group) {
                        return $group->name;
                    }
                })
                ->addColumn('followup_name', function (Member $member) {
                    return get_admin_name($member->follow_up_by, true);
                })
                ->addColumn('flat_no', function (Member $member) {
                    return $member->flat_no;
                })
                ->addColumn('building_name', function (Member $member) {
                    return $member->building_name;
                })
                ->addColumn('landmark', function (Member $member) {
                    return $member->landmark;
                })
                ->addColumn('street_name', function (Member $member) {
                    return $member->street_name;
                })
                ->addColumn('postcode', function (Member $member) {
                    return $member->postcode;
                })
                ->addColumn('full_address', function (Member $member) {
                    return $member->flat_no . ' ' . $member->building_name . ', ' . $member->landmark . ', ' . $member->street_name . ' ' . $member->postcode;
                })
                ->addColumn('member_type', function (Member $member) {
                    return get_member_type_badge($member->member_is);
                })
                ->addColumn('ambrish_code', function (Member $member) {
                    return $member->ambrish_code;
                })
                ->addColumn('action', function (Member $member) {
                    ob_start(); ?>
                <?php if (current_user_can('edit', 'members', $member->id) === true) { ?>
                    <a class="mr-2" href="<?php echo route("members.edit", $member->id); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>
                <?php } ?>

                <a class="mr-2" data-toggle="modal" data-target="#modal-member-view" data-remote="<?php echo route("members.show", $member->id); ?>" href="javascript:void;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>

                <a target="_blank" class="mr-2" href="https://wa.me/<?= get_member_phonecode($member->country_id); ?><?php echo trim($member->mobile); ?>?text=<?php echo ('Jay Swaminarayan%0a🙏 Das Na Das 🙏'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                        <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
                    </svg>
                </a>

                <?php if (current_user_can('delete', 'members', $member->id) === true) { ?>
                    <button type="submit" class="text-danger confirm-delete btn p-0" data-action="<?php echo route("members.destroy", $member->id); ?>">
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
                ->rawColumns(['name', 'photo', 'member_type', 'mobile', 'email', 'ref_name', 'followup_name', 'created_by', 'attending_sabha', 'action'])
                ->make(true);
        }
        return view('member.index');
    }

    //
    public function getMemberData($request)
    {
        $records = Member::select('*');

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

        elseif (get_current_admin_level() == 'Group_Admin') :
            $records->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id)->where('sabha_id', Auth::user()->sabha_id)->where('group_id', Auth::user()->group_id);

        elseif (get_current_admin_level() == 'Followup_Admin') :
            $records->where('country_id', Auth::user()->country_id)->where('state_id', Auth::user()->state_id)->where('pradesh_id', Auth::user()->pradesh_id)->where('zone_id', Auth::user()->zone_id)->where('sabha_id', Auth::user()->sabha_id)->where('follow_up_by', Auth::user()->id);

        endif;

        //Datatable Search
        if ($request->search['value']) {
            $keyword = $request->search['value'];
            //dd($keyword);
            $records->where(function ($query) use ($keyword) {
                $query->orWhere('first_name', 'like', "$keyword%")->orWhere('middle_name', 'like', "$keyword%")->orWhere('surname', 'like', "$keyword%")->orWhere('mobile', 'like', "$keyword%")->orWhere(DB::raw('CONCAT_WS(" ", first_name, surname)'), 'like', "%$keyword%");
            });
        }


        //filterReferenceId
        if ($request->filterReferenceId) {
            $refId = $request->filterReferenceId;
            $refNmae = get_member_fullname($refId);
            $records->where(function ($query) use ($refId, $refNmae) {
                $query->orWhere('ref_name', 'like', "$refNmae")->orWhere('reference_id', '=', $refId);
            });
        }

        //search filterLastSabhaStatus
        if ($request->filterLastSabhaStatus) {
            $lastSabhaStatus = $request->filterLastSabhaStatus;
            if ($lastSabhaStatus == 'Present') {
                $mIds = DB::select("SELECT member_id FROM attenders AS a1 WHERE YEAR(created_at) = '" . date('Y') . "' AND present = 'Yes' GROUP BY member_id ORDER BY attendance_id DESC");
                $mIds = array_column($mIds, 'member_id');
                //dd( $mIds);
                $records->whereIn('id', $mIds);
            } else if ($lastSabhaStatus == 'Absent') {
                $mIds = DB::select("SELECT member_id FROM attenders AS a1 WHERE YEAR(created_at) = '" . date('Y') . "' AND present = 'No' GROUP BY member_id ORDER BY attendance_id DESC");
                $mIds = array_column($mIds, 'member_id');
                //dd( $mIds);
                $records->whereIn('id', $mIds);
            }
        }

        //search filterSabhaRegularity
        if ($request->filterSabhaRegularity) {
            $regularity = $request->filterSabhaRegularity;
            if ($regularity == 'Regular') {
                $mIds = DB::select("SELECT a1.member_id FROM attenders AS a1 WHERE YEAR(a1.created_at) = '" . date('Y') . "' AND( SELECT COUNT(*) FROM attenders AS a2 WHERE a2.member_id = a1.member_id AND a2.present = 'Yes' ORDER BY a2.id DESC LIMIT 4 ) >= " . REGULAR_LIMIT . " GROUP BY a1.member_id");
                $mIds = array_column($mIds, 'member_id');
                //dd( $mIds);
                $records->whereIn('id', $mIds);
            } else if ($regularity == 'Irregular') {
                $mIds = DB::select("SELECT a1.member_id FROM attenders AS a1 WHERE YEAR(a1.created_at) = '" . date('Y') . "' AND( SELECT COUNT(*) FROM attenders AS a2 WHERE a2.member_id = a1.member_id AND a2.present = 'Yes' ORDER BY a2.id DESC LIMIT 4 ) < " . REGULAR_LIMIT . " GROUP BY a1.member_id");
                $mIds = array_column($mIds, 'member_id');
                //dd( $mIds);
                $records->whereIn('id', $mIds);
            } else if ($regularity == 'Fresh') {
                $mIds = DB::select("SELECT a1.member_id FROM attenders AS a1 WHERE YEAR(a1.created_at) = '" . date('Y') . "' AND( SELECT COUNT(*) FROM attenders AS a2 WHERE a2.member_id = a1.member_id AND a2.present = 'Yes' ) < " . FRESH_LIMIT . " GROUP BY a1.member_id");
                $mIds = array_column($mIds, 'member_id');
                //dd( $mIds);
                $records->whereIn('id', $mIds);
            }
        }

        //search filterFromAge filterToAge
        if ($request->filterFromAge && $request->filterToAge) {
            $records->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR,members.date_of_birth,CURDATE())'), array($request->filterFromAge, $request->filterToAge));
        }

        //search filterMemberTags
        if ($request->filterMemberTags) {
            $records->whereIn('id', MemberTag::whereIn('tag_id', $request->filterMemberTags)->pluck('member_id')->toArray());
        }

        //search by zone
        if ($request->filterZone_id) {
            $records->where('zone_id', $request->filterZone_id);
        }

        //search by sabha
        if ($request->filterSabha_id) {
            $records->where('sabha_id', $request->filterSabha_id);
        }

        //search by group
        if ($request->filterGroup_id) {
            $records->where('group_id', $request->filterGroup_id);
        }

        //search by followup
        if ($request->filterFollowup_id) {
            $records->where('follow_up_by', $request->filterFollowup_id);
        }

        //search by memberType
        if ($request->filterMemberType) {
            $records->whereIn('member_is', $request->filterMemberType);
        }

        //search by attendingSabha
        if ($request->filterAttendingSabha) {
            $records->where('attending_sabha', $request->filterAttendingSabha);
        }

        //search by BloodGroup
        if ($request->filterBloodGroup) {
            $records->where('blood_group', $request->filterBloodGroup);
        }

        //search by marital_status	
        if ($request->filterMaritalStatus) {
            $records->where('marital_status', $request->filterMaritalStatus);
        }

        //search by gender
        if ($request->filterGender) {
            $records->where('gender', $request->filterGender);
        }

        //Orderby name
        if ($request->order[0]['column']) {
            $records->orderBy('first_name', $request->order[0]['dir']);
        } else {
            $records->orderby('first_name', 'asc');
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
        return view('member.create');
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

        // Validation
        $args = [
            'first_name' => ['required', 'string', 'max:30', new nameRule],
            'middle_name' => ['required', 'string', 'max:30', new nameRule],
            'surname' => ['required', 'string', 'max:30', new nameRule],
            'date_of_birth' => ['required'],
            // 'dob_day' => ['required'],
            // 'dob_month' => ['required'],
            // 'dob_year' => ['required', 'digits_between:4,4'],
            'mobile' => ['required', 'digits_between:10,11', 'unique:members'],
            //'flat_no' => ['required', 'string'],
            //'building_name' => ['required', 'string'],
            //'landmark' => ['string'],
            //'street_name' => ['required', 'string'],
            //'postcode' => ['required', 'string', 'min:6', 'max:7'],
            'sabha_id' => ['required'],
            'zone_id' => ['required'],
            'member_is' => ['required', 'string'],
        ];
        if ($inputs['email'] != '') {
            $args['email'] = ['required', 'string', 'email', 'max:255', 'unique:members'];
        }

        //if ($inputs['attending_sabha'] == 'Yes') {
        $args['group_id'] = ['required'];
        $args['follow_up_by'] = ['required'];
        //}

        //Sanitization
        $inputs['first_name'] = strip_tags($request->first_name);
        $inputs['middle_name'] = strip_tags($request->middle_name);
        $inputs['surname'] = strip_tags($request->surname);
        $inputs['nick_name'] = strip_tags($request->nick_name);
        $inputs['flat_no'] = strip_tags($request->flat_no);
        $inputs['building_name'] = strip_tags($request->building_name);
        $inputs['landmark'] = strip_tags($request->landmark);
        $inputs['street_name'] = strip_tags($request->street_name);

        //Validation
        $validator = Validator::make($inputs, $args);
        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()]);
        }

        //ref_name
        if ($request->reference_id != 0 && $request->reference_id != '') {
            $inputs['ref_name'] = get_member_fullname($request->reference_id);
        }

        $inputs['first_name'] = ucfirst($request->first_name);
        $inputs['middle_name'] = ucfirst($request->middle_name);
        $inputs['surname'] = ucfirst($request->surname);

        //$inputs['date_of_birth'] = $request->dob_year . '-' . $request->dob_month . '-' . $request->dob_day;
        $inputs['date_of_birth'] = date('Y-m-d', strtotime($request->date_of_birth));
        if (in_array(get_current_admin_level(), ['Sabha_Admin', 'Group_Admin', 'Followup_Admin'])) :
            $objSabha = get_sabha_by('id', Auth::user()->sabha_id);
        else :
            $objSabha = get_sabha_by('id', $inputs['sabha_id']);
        endif;
        $inputs['country_id'] = $objSabha->country_id;
        $inputs['state_id'] = $objSabha->state_id;
        $inputs['city_id'] = $objSabha->city_id;
        $inputs['pradesh_id'] = $objSabha->pradesh_id;
        $inputs['zone_id'] = $objSabha->zone_id;

        $inputs['created_by'] = Auth::user()->id;

        $carbonDateTime = Carbon::now();
        $inputs['created_at'] = $carbonDateTime->toDateTimeString();
        $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
        $member = Member::create($inputs);

        //Add attendance
        add_attendance_when_member_add_or_update($member->id);

        add_admin_activity_logs("<b>" . $member->first_name . " " . $member->surname . "</b> has been added in <b>{$objSabha->name}</b> sabha", "Member", "Add Member", $member->id);

        return response()->json(['success' => 1, 'redirect' => route("members.edit", ['member' => $member->id, 'tab' => 'otherInfo']), 'errors' => []]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member)
    {
        $data = [];
        /*
        $joining_date = $member->joining_date;
        if ($member->joining_date != NULL) {
            $joining_date = Carbon::parse($member->joining_date)->format('j F, Y');
        }

        $date_of_birth = Carbon::parse($member->date_of_birth)->format('jS F, Y');

        $data['mdata'] = [
            'basicInfo' => [
                'First Name' => $member->first_name,
                'Middle Name' => $member->middle_name,
                'Surname' => $member->surname,
                'Nick Name' => $member->nick_name,
                'Gender' => $member->gender,
                'Date of Birth' => $date_of_birth,
                'Mobile' => $member->mobile,
                'Email' => $member->email,
                'Flat No' => $member->flat_no,
                'Building Name' => $member->building_name,
                'Landmark' => $member->landmark,
                'Street Name' => $member->street_name,
                'Postcode' => $member->postcode,
                'Attending Sabha?' => $member->attending_sabha,
                'Sabha' => (isset(get_sabha_by('id', $member->sabha_id)->name) ? get_sabha_by('id', $member->sabha_id)->name : ''),
                'Follow Up Name' => '',
                'Member Type' => get_member_type_badge($member->member_is),
                'Joining Date' => $joining_date,
                'Reference Name' => ($member->reference_id == 0 ? $member->ref_name : get_member_fullname($member->reference_id))
            ],
            'otherInfo' => [
                'Educational Qualification' => $member->edu_qualification,
                'Major Subject' => $member->edu_subject,
                'School/College/Institute/University' => $member->school_college,
                'Educational Status' => $member->edu_status,
                'Occupation' => $member->occupation,
                'Name Of The Organization' => $member->organization,
                'Industry' => $member->industry,
                'Designation' => $member->designation,
                'Marital Status' => $member->marital_status,
                'Anniversery Date' => $member->anniversery_date,
                'Blood Group' => $member->blood_group,
                'Performing Puja?' => $member->performing_puja,
                'Nishtawan?' => $member->nishtawan,
                'Ambrish Code' => $member->ambrish_code,
                'Languages Known' => ($member->languages_known != NULL ? implode(',', unserialize($member->languages_known)) : '')
            ],
            'photo' => $member->photo
        ];
        */

        return view('member.view', $data)->with('member', $member);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        if (in_array(get_current_admin_level(), ['Sabha_Admin', 'Group_Admin', 'Followup_Admin'])) {
            $data['sabha_id'] = Auth::user()->sabha_id;
        } else {
            $data['sabha_id'] = $member->sabha_id;
        }
        $objSabha = get_sabha_by('id', $data['sabha_id']);
        $data['sabha_name'] = $objSabha->name;

        if (in_array(get_current_admin_level(), ['Sabha_Admin', 'Group_Admin', 'Followup_Admin'])) {
            $data['zone_id'] = Auth::user()->zone_id;
        } else {
            $data['zone_id'] = $member->zone_id;
        }
        $objZone = get_zone_by('id', $data['zone_id']);
        $data['zone_name'] = $objZone->name;

        $data['group_name'] = '';
        if ($member->group_id) {
            $objGroup = get_group_by('id', $member->group_id);
            $data['group_name'] = $objGroup->name;
        }
        $data['followup_name'] = '';
        if ($member->follow_up_by) {
            $objAdmin = get_admin_by('id', $member->follow_up_by);
            $data['followup_name'] = $objAdmin->name;
        }


        $memberTags = DB::select("SELECT mt.`tag_id`,(SELECT name FROM `tags_master` WHERE id=mt.tag_id) as tagName FROM `member_tags` as mt WHERE mt.member_id=$member->id");
        $data['memberTags'] = $memberTags;


        // $dobArray = explode('-', $member->date_of_birth);
        // $data['dob_day'] = $dobArray[2];
        // $data['dob_month'] = $dobArray[1];
        // $data['dob_year'] = $dobArray[0];

        // $joining_Array = explode('-', $member->joining_date);
        // $data['joining_day'] = (isset($joining_Array[2]) ? $joining_Array[2] : '');
        // $data['joining_month'] = (isset($joining_Array[1]) ? $joining_Array[1] : '');
        // $data['joining_year'] = (isset($joining_Array[0]) ? $joining_Array[0] : '');

        // $anniversery_Array = explode('-', $member->anniversery_date);
        // $data['anniversery_day'] = (isset($anniversery_Array[2]) ? $anniversery_Array[2] : '');
        // $data['anniversery_month'] = (isset($anniversery_Array[1]) ? $anniversery_Array[1] : '');
        // $data['anniversery_year'] = (isset($anniversery_Array[0]) ? $anniversery_Array[0] : '');

        $data['languages_known'] = ($member->languages_known == NULL ? [] : unserialize($member->languages_known));

        return view('member.edit', compact('member'))->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
        $inputs = $request->all();

        if ($request->tabName == 'memberBasic') {
            // Validation
            $args = [
                'first_name' => ['required', 'string', 'max:30', new nameRule],
                'middle_name' => ['required', 'string', 'max:30', new nameRule],
                'surname' => ['required', 'string', 'max:30', new nameRule],
                'date_of_birth' => ['required'],
                // 'dob_day' => ['required'],
                // 'dob_month' => ['required'],
                // 'dob_year' => ['required', 'digits_between:4,4'],
                'mobile' => ['required', 'digits_between:10,11', 'unique:members,mobile,' . $member->id],
                //'flat_no' => ['required', 'string'],
                //'building_name' => ['required', 'string'],
                //'landmark' => ['string'],
                //'street_name' => ['required', 'string'],
                //'postcode' => ['required', 'string', 'min:6', 'max:7'],
                'sabha_id' => ['required'],
                'zone_id' => ['required'],
                'member_is' => ['required', 'string'],
            ];
            if ($inputs['email'] != '') {
                $args['email'] = ['required', 'string', 'email', 'max:255', 'unique:members,email,' . $member->id];
            }

            //if ($inputs['attending_sabha'] == 'Yes') {
            $args['group_id'] = ['required'];
            $args['follow_up_by'] = ['required'];
            //}

            //Sanitization
            $inputs['first_name'] = strip_tags($request->first_name);
            $inputs['middle_name'] = strip_tags($request->middle_name);
            $inputs['surname'] = strip_tags($request->surname);
            $inputs['nick_name'] = strip_tags($request->nick_name);
            $inputs['flat_no'] = strip_tags($request->flat_no);
            $inputs['building_name'] = strip_tags($request->building_name);
            $inputs['landmark'] = strip_tags($request->landmark);
            $inputs['street_name'] = strip_tags($request->street_name);

            //Validation
            $validator = Validator::make($inputs, $args);
            if ($validator->fails()) {
                return response()->json(['success' => 0, 'errors' => $validator->errors()]);
            }

            //ref_name
            if ($request->reference_id != 0 && $request->reference_id != '') {
                $inputs['ref_name'] = get_member_fullname($request->reference_id);
            }

            $inputs['first_name'] = ucfirst($request->first_name);
            $inputs['middle_name'] = ucfirst($request->middle_name);
            $inputs['surname'] = ucfirst($request->surname);

            //$inputs['date_of_birth'] = $request->dob_year . '-' . $request->dob_month . '-' . $request->dob_day;
            $inputs['date_of_birth'] = date('Y-m-d', strtotime($request->date_of_birth));
            if ($request->joining_date == '') {
                $inputs['joining_date'] = NULL;
            } else {
                $inputs['joining_date'] = date('Y-m-d', strtotime($request->joining_date));
            }

            if (in_array(get_current_admin_level(), ['Sabha_Admin', 'Group_Admin', 'Followup_Admin'])) :
                $objSabha = get_sabha_by('id', Auth::user()->sabha_id);
            else :
                $objSabha = get_sabha_by('id', $inputs['sabha_id']);
            endif;
            $inputs['country_id'] = $objSabha->country_id;
            $inputs['state_id'] = $objSabha->state_id;
            $inputs['city_id'] = $objSabha->city_id;
            $inputs['pradesh_id'] = $objSabha->pradesh_id;
            $inputs['zone_id'] = $objSabha->zone_id;

            $carbonDateTime = Carbon::now();
            $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
            $member->update($inputs);

            //Add attendance
            add_attendance_when_member_add_or_update($member->id);

            $objSabha = get_sabha_by('id', $member->sabha_id);
            add_admin_activity_logs("<b>" . $member->first_name . " " . $member->surname . "</b> has been updated which belongs to the <b>{$objSabha->name}</b> sabha", "Member", "Edit Member", $member->id);

            Session::flash('success', 'Member has been updated successfully.');
            return response()->json(['success' => 1, 'redirect' => route("members.index"), 'errors' => []]);
        } else if ($request->tabName == 'memberOther') {
            // Validation
            $args = [
                'edu_qualification' => ['required', 'string'],
                'edu_subject' => ['required', 'string'],
                'edu_status' => ['required', 'string'],
            ];
            if ($request->edu_subject == 'Others') {
                $args['edu_other'] = ['required', 'string', 'max:100'];
            } else {
                $inputs['edu_other'] = '';
            }
            $validator = Validator::make($request->all(), $args);
            if ($validator->fails()) {
                return response()->json(['success' => 0, 'errors' => $validator->errors()]);
            }


            if ($request->anniversery_date == '') {
                $inputs['anniversery_date'] = NULL;
            } else {
                $inputs['anniversery_date'] = date('Y-m-d', strtotime($request->anniversery_date));
            }

            // if ($request->anniversery_day != '' && $request->anniversery_month != '' && $request->anniversery_year != '') {
            //     $inputs['anniversery_date'] = $request->anniversery_year . '-' . $request->anniversery_month . '-' . $request->anniversery_day;
            // } else {
            //     $inputs['anniversery_date'] = NULL;
            // }

            if (isset($request->languages_known) && !empty($request->languages_known)) {
                $inputs['languages_known'] = serialize($request->languages_known);
            } else {
                $inputs['languages_known'] = NULL;
            }

            $carbonDateTime = Carbon::now();
            $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
            $member->update($inputs);

            $objSabha = get_sabha_by('id', $member->sabha_id);
            add_admin_activity_logs("<b>" . $member->first_name . " " . $member->surname . "</b> has been updated which belongs to the <b>{$objSabha->name}</b> sabha", "Member", "Edit Member", $member->id);

            Session::flash('success', 'Member has been updated successfully.');
            return response()->json(['success' => 1,  'redirect' => route("members.index"), 'errors' => []]);
        } else if ($request->tabName == 'memberPhoto') {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($photo = $request->file('photo')) {
                $photo_name = time() . '_photo_.' . $photo->extension();
                $photo->move(public_path('uploads/member_photo'), $photo_name);
                $inputs['photo'] = $photo_name;

                $carbonDateTime = Carbon::now();
                $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
                $member->update($inputs);
            }

            $objSabha = get_sabha_by('id', $member->sabha_id);
            add_admin_activity_logs("<b>" . $member->first_name . " " . $member->surname . "</b> has been updated which belongs to the <b>{$objSabha->name}</b> sabha", "Member", "Edit Member", $member->id);

            Session::flash('success', 'Photo has been updated successfully.');
            return redirect()->route("members.edit", ['member' => $member->id]);
        } else if ($request->tabName == 'memberTag') {

            MemberTag::where('member_id', $member->id)->delete();

            if (!empty($request->tag_ids)) {
                foreach ($request->tag_ids as $tagId) {
                    $memberTagRow = [
                        'member_id' =>  $member->id,
                        'tag_id' => $tagId,
                        'created_by' => Auth::user()->id
                    ];
                    MemberTag::create($memberTagRow);
                }
            }

            $objSabha = get_sabha_by('id', $member->sabha_id);
            add_admin_activity_logs("<b>" . $member->first_name . " " . $member->surname . "</b> has been add tags which belongs to the <b>{$objSabha->name}</b> sabha", "Member", "Edit Member", $member->id);

            Session::flash('success', 'Member has been updated successfully.');
            return response()->json(['success' => 1,  'redirect' => route("members.index"), 'errors' => []]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        $member->delete();

        //Remove reference
        Member::where('reference_id', $member->id)->update(array('reference_id' => 0, 'ref_name' => $member->first_name . " " . $member->middle_name . " " . $member->surname));

        $objSabha = get_sabha_by('id', $member->sabha_id);
        add_admin_activity_logs("<b>" . $member->first_name . " " . $member->surname . "</b> has been deleted which belongs to the <b>{$objSabha->name}</b> sabha", "Member", "Delete Member", $member->id);

        return response()->json(['success' => 1, 'errors' => []]);
    }

    public function subjectListHtml(Request $request)
    {
        $data['index'] = isset($request->index_key) ? $request->index_key : '';
        $data['selected'] =  isset($request->selected) ? $request->selected : '';
        if (isset($request->return_json) && $request->return_json == 1) {
            $html = view('member.option_edu_subject', $data)->render();
            return response()->json(['success' => 1, 'html' => $html]);
        }
        return view('member.option_edu_subject', $data);
    }

    public function ajaxAutocompleteSearch(Request $request)
    {
        $members = [];

        $search = "1=1";
        if ($request->has('q')) {
            $search = "( CONCAT(m.first_name,' ',m.surname) LIKE '%$request->q' or (m.first_name LIKE '$request->q%' OR m.surname LIKE '$request->q%') or m.mobile LIKE '$request->q%')";
        }
        $where = (isset($request->whereSql) && trim($request->whereSql) != '' ? $request->whereSql : '');

        $query = "SELECT m.id,m.first_name,m.middle_name,m.surname,m.email,m.mobile, CONCAT(m.first_name,' ',m.middle_name,' ',m.surname,' | ',(SELECT name FROM zones WHERE id = m.zone_id)) as name FROM members as m WHERE $search $where order by m.first_name,m.middle_name,m.surname";
        $members = DB::select($query);

        return response()->json($members);
    }
}
