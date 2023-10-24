<div class="modal-header p-0">
    {{-- <h4 class="modal-title">Member Details</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg></span>
    </button> --}}
    <ul class="nav nav-tabs" id="member-view-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="member-form-tab-contact" data-toggle="pill"
                href="#member-form-tab-contact-content" role="tab" aria-controls="member-form-tab-contact"
                aria-selected="false">Contact Details</a>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link" id="member-form-tab-basic" data-toggle="pill" href="#member-form-tab-basic-content"
                role="tab" aria-controls="member-form-tab-basic" aria-selected="true">Basic Information</a>
        </li> --}}
        {{-- <li class="nav-item">
            <a class="nav-link" id="member-form-tab-otherInfo" data-toggle="pill"
                href="#member-form-tab-otherInfo-content" role="tab" aria-controls="member-form-tab-otherInfo"
                aria-selected="false">Other Information</a>
        </li> --}}
    </ul>
</div>
<div class="modal-body p-0">
    <div class="tab-content" id="member-view-tab-tabContent">
        <div class="tab-pane fade show active pt-2" id="member-form-tab-contact-content" role="tabpanel"
            aria-labelledby="member-form-tab-contact">
            <div class="d-flex align-items-center">
                <div class="col-3 mr-1 text-center">
                    <?php
                    $_photoUrl = asset('assets/img/yuvak-placehoder.png');
                    if ($member->photo) {
                        $_photoUrl = url('uploads/member_photo') . '/' . $member->photo;
                    }
                    ?>
                    <img class="img-yuvak-view shadow" src="<?php echo $_photoUrl; ?>" alt="yuvak">
                </div>
                <div class="col-9">
                    <h6 class="mb-1 d-flex align-items-center">
                        <span>{{ strtoupper($member->first_name . ' ' . $member->middle_name . ' ' . $member->surname) }}</span>
                        <span class="ml-2">{!! get_member_type_badge($member->member_is) !!}</span>
                    </h6>
                    <?php
                    $zone = get_zone_by('id', $member->zone_id);
                    $sabha = get_sabha_by('id', $member->sabha_id);
                    ?>
                    <p class="mb-1 d-flex align-items-center">
                        <?php if ($zone) { ?>
                        <span class="mr-1">{{ $zone->name }}</span>
                        <?php } ?>
                        <?php if ($sabha) { ?>
                        <span class="mr-1 pl-1 border-left pr-1 border-right">{{ $sabha->name }}</span>
                        <?php } ?>
                        <?php if ($member->attending_sabha == "Yes") { ?>
                        <small class="badge badge-success" style="font-size: 80%;">Yes</small>
                        <?php } else { ?>
                        <small class="badge badge-danger" style="font-size: 80%;">No</small>
                        <?php } ?>
                    </p>
                    <p class="mb-0 d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.87c1.355 0 2.697.055 4.024.165C17.155 8.51 18 9.473 18 10.608v2.513m-3-4.87v-1.5m-6 1.5v-1.5m12 9.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0L3 16.5m15-3.38a48.474 48.474 0 00-6-.37c-2.032 0-4.034.125-6 .37m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.17c0 .62-.504 1.124-1.125 1.124H4.125A1.125 1.125 0 013 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 016 13.12M12.265 3.11a.375.375 0 11-.53 0L12 2.845l.265.265zm-3 0a.375.375 0 11-.53 0L9 2.845l.265.265zm6 0a.375.375 0 11-.53 0L15 2.845l.265.265z" />
                        </svg>
                        <span
                            class="ml-1 pl-1 border-left">{{ date('jS F, Y', strtotime($member->date_of_birth)) }}</span>
                        <a class="ml-2 pl-2 border-left" target="_blank"
                            href="https://wa.me/<?= get_member_phonecode($member->country_id); ?><?php echo trim($member->mobile); ?>?text=<?php echo 'Jay Swaminarayan Das Na Das%0a%0aWish you a very Happy Birthday 🎂'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-whatsapp" viewBox="0 0 16 16">
                                <path
                                    d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
                            </svg>
                        </a>
                        <a class="ml-2 pl-2 border-left" target="_blank"
                            href="sms:{{ $member->mobile }}?&body=<?php echo 'Jay Swaminarayan Das Na Das%0a%0aWish you a very Happy Birthday 🎂'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                            </svg>
                        </a>
                    </p>
                </div>
            </div>
            <hr>

            <div class="d-flex align-items-center">
                <div class="col-4 border-right">
                    <a target="_blank" href="tel:{{ $member->mobile }}"
                        class="d-flex align-items-center justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                        {{ $member->mobile }}
                    </a>
                </div>
                <div class="col-4 border-right">
                    <a target="_blank" href="sms:{{ $member->mobile }}?&body=<?php echo 'Jay Swaminarayan%0a🙏 Das Na Das 🙏'; ?>"
                        class="d-flex align-items-center justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                        </svg>&nbsp;Message
                    </a>
                </div>
                <div class="col-4">
                    <a target="_blank" href="https://wa.me/<?= get_member_phonecode($member->country_id); ?><?php echo trim($member->mobile); ?>?text=<?php echo 'Jay Swaminarayan%0a🙏 Das Na Das 🙏'; ?>"
                        class="d-flex align-items-center justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-whatsapp" viewBox="0 0 16 16">
                            <path
                                d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
                        </svg>&nbsp;Whatsapp
                    </a>
                </div>
            </div>
            <hr>

            <div class="d-flex align-items-center">
                <div class="col-6 text-center border-right">
                    <?php $objGroup = get_group_by('id', $member->group_id); ?>
                    @if ($objGroup)
                        <div><strong>{{ $objGroup->name }}</strong></div>
                    @else
                        <div><strong>N/A</strong></div>
                    @endif
                    <small class="text-muted">Group</small>
                </div>
                <div class="col-6 text-center border-right">
                    <?php $objAdmin = get_admin_by('id', $member->follow_up_by); ?>
                    @if ($objAdmin)
                        <div><strong>{{ $objAdmin->name }}</strong></div>
                    @else
                        <div><strong>N/A</strong></div>
                    @endif
                    <small class="text-muted">Followup</small>
                </div>
            </div>
            <hr>

            <div class="d-flex align-items-center">
                <div class="col-3 text-center border-right">
                    Attendance
                </div>
                <div class="col-3 text-center border-right">
                    <div><strong>{{ get_member_total_attendance($member) }}</strong></div>
                    <small class="text-muted">Total</small>
                </div>
                <div class="col-3 text-center border-right">
                    <div><strong>{{ get_member_total_present_attendance($member) }}</strong></div>
                    <small class="text-muted">Present</small>
                </div>
                <div class="col-3 text-center">
                    <div><strong>{{ number_format(get_member_attendance_percentage($member), 2) }}</strong></div>
                    <small class="text-muted">%</small>
                </div>
            </div>
            <hr>

            <div class="d-flex align-items-center">
                <div class="col-4 text-center border-right">
                    <?php
                    $joining_date = 'N/A';
                    if ($member->joining_date != null && $member->joining_date != '0000-00-00') {
                        $joining_date = date('j M, Y', strtotime($member->joining_date));
                    }
                    ?>
                    <div><strong>{{ $joining_date }}</strong></div>
                    <small class="text-muted">Joining Date</small>
                </div>
                <div class="col-4 text-center border-right">
                    <div><strong>{{ get_member_last_attended($member) }}</strong></div>
                    <small class="text-muted">Last Attended</small>
                </div>
                <div class="col-4 text-center border-right">
                    <?php
                    $ref_name = '';
                    if ($member->reference_id == 0) {
                        $ref_name = $member->ref_name;
                    } else {
                        $ref_name = get_member_fullname($member->reference_id);
                    }
                    ?>
                    <div><strong>{{ $ref_name }}</strong></div>
                    <small class="text-muted">Reference Name</small>
                </div>
            </div>
            <hr>

            <div class="d-flex align-items-center">
                <div class="col-12">

                    <p class="mb-0 d-flex align-items-center justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <span class="ml-2">
                            @if ($member->flat_no)
                                {{ $member->flat_no }}
                            @endif
                            @if ($member->building_name)
                                {{ $member->building_name }},
                            @endif
                            @if ($member->landmark)
                                {{ $member->landmark }},
                            @endif
                            @if ($member->street_name)
                                {{ $member->street_name }},
                            @endif
                            @if ($member->postcode)
                                {{ $member->postcode }}
                            @endif
                        </span>
                    </p>
                </div>
            </div>
            <hr>

        </div>
        {{-- <div class="tab-pane fade" id="member-form-tab-basic-content" role="tabpanel"
            aria-labelledby="member-form-tab-basic">
            <table class="table table-bordered table-striped">
                @foreach ($mdata['basicInfo'] as $key => $value)
                    @if ($value != null)
                        <tr>
                            <th>{!! $key !!}:</th>
                            <td>{!! $value !!}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div> --}}
        {{-- <div class="tab-pane fade" id="member-form-tab-otherInfo-content" role="tabpanel"
            aria-labelledby="member-form-tab-otherInfo">
            <table class="table table-bordered table-striped">
                @foreach ($mdata['otherInfo'] as $key => $value)
                    @if ($value != null)
                        <tr>
                            <th>{!! $key !!}:</th>
                            <td>{!! $value !!}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div> --}}
    </div>
</div>
{{-- <div class="modal-footer justify-content-end p-0">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg></span>
    </button>
</div> --}}
