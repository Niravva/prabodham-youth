@extends('layouts.app')

@section('title')
    Birthday
@stop

@section('left_header_content')
    Birthday
@endsection

@section('right_header_content')
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-share">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
        </svg>
    </button>
    <button type="button" class="btn btn-default btn-show-checkbox">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </button>
@endsection

@section('left_header_back_button')
    <a class="nav-link mr-3 pl-1" href="{{ $_backURL }}" role="button">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
    </a>
@endsection

@section('content')

    <div class="row birthday-yuvak-list-page" style="margin-top: -0.5rem;">
        <div class="col-sm-12">
            <ul class="nav nav-tabs" id="birthday-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link <?php echo $request->type == 'today' ? 'active' : ''; ?>" id="birthday-today-tab" data-toggle="pill"
                        href="#birthday-today-tab-content" role="tab" aria-controls="birthday-tab" aria-selected="true"
                        data-message-label="<?= date('d-M') ?>">TODAY</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $request->type == 'tomorrow' ? 'active' : ''; ?>" id="birthday-tomorrow-tab" data-toggle="pill"
                        href="#birthday-tomorrow-tab-content" role="tab" aria-controls="birthday-tab"
                        aria-selected="true" data-message-label="<?= date('d-M', strtotime('tomorrow')) ?>">TOMORROW</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $request->type == 'thisweek' ? 'active' : ''; ?>" id="birthday-thisweek-tab" data-toggle="pill"
                        href="#birthday-thisweek-tab-content" role="tab" aria-controls="birthday-tab"
                        aria-selected="true" data-message-label="this week:">THIS WEEK</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $request->type == 'thismonth' ? 'active' : ''; ?>" id="birthday-thismonth-tab" data-toggle="pill"
                        href="#birthday-thismonth-tab-content" role="tab" aria-controls="birthday-tab"
                        aria-selected="true" data-message-label="this month:">THIS MONTH</a>
                </li>
            </ul>
            <div class="yuvak-search-bar clearfix">
                <input type="search" class="form-control" id="inputSearchYuvak" placeholder="Search here...">
            </div>
        </div>
        <div class="col-sm-12 p-0">
            <div class="tab-content mt-5 pt-5" id="birthday-tab-content">
                <div class="tab-pane fade <?php echo $request->type == 'today' ? 'show active' : ''; ?>" id="birthday-today-tab-content" role="tabpanel"
                    aria-labelledby="birthday-tab">
                    <ul class="list birthday-yuvak-list">
                        @foreach ($data['todayBirthday'] as $member)
                            {!! birthday_loop_itm_html($member) !!}
                        @endforeach
                    </ul>
                </div>
                <div class="tab-pane fade <?php echo $request->type == 'tomorrow' ? 'show active' : ''; ?>" id="birthday-tomorrow-tab-content" role="tabpanel"
                    aria-labelledby="birthday-tab">
                    <ul class="list birthday-yuvak-list">
                        @foreach ($data['tomorrowBirthday'] as $member)
                            {!! birthday_loop_itm_html($member) !!}
                        @endforeach
                    </ul>
                </div>
                <div class="tab-pane fade <?php echo $request->type == 'thisweek' ? 'show active' : ''; ?>" id="birthday-thisweek-tab-content" role="tabpanel"
                    aria-labelledby="birthday-tab">
                    <ul class="list birthday-yuvak-list">
                        @foreach ($data['thisWeekBirthday'] as $member)
                            {!! birthday_loop_itm_html($member) !!}
                        @endforeach
                    </ul>
                </div>
                <div class="tab-pane fade <?php echo $request->type == 'thismonth' ? 'show active' : ''; ?>" id="birthday-thismonth-tab-content" role="tabpanel"
                    aria-labelledby="birthday-tab">
                    <ul class="list birthday-yuvak-list">
                        @foreach ($data['thisMonthBirthday'] as $member)
                            {!! birthday_loop_itm_html($member) !!}
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal-member-view">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="text-center p-5">
                    <i class="text-muted fas fa-spinner fa-spin" style="font-size: 25px;"></i>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal-share" data-backdrop="static">
        <div class="modal-dialog modal-sm modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Share</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg></span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <table class="table mb-0">
                        <tr>
                            <th>Name</th>
                            <td class="text-right">
                                <div class="icheck-primary m-0">
                                    <input class="inputShareQ" id="shareNameQ" type="checkbox" value="1" checked
                                        disabled>
                                    <label for="shareNameQ"> </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Mobile</th>
                            <td class="text-right">
                                <div class="icheck-primary m-0">
                                    <input class="inputShareQ" id="shareMobileQ" type="checkbox" value="1" checked
                                        disabled>
                                    <label for="shareMobileQ"> </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Attending Sabha</th>
                            <td class="text-right">
                                <div class="icheck-primary m-0">
                                    <input class="inputShareQ" id="shareAttendingsabhaQ" type="checkbox" value="1">
                                    <label for="shareAttendingsabhaQ"> </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Sabha</th>
                            <td class="text-right">
                                <div class="icheck-primary m-0">
                                    <input class="inputShareQ" id="shareSabhaQ" type="checkbox" value="1">
                                    <label for="shareSabhaQ"> </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Zone</th>
                            <td class="text-right">
                                <div class="icheck-primary m-0">
                                    <input class="inputShareQ" id="shareZoneQ" type="checkbox" value="1">
                                    <label for="shareZoneQ"> </label>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <form id="whatsapp-share-form" method="GET" action="https://wa.me/">
                        <textarea name="text" id="whatsapp-text" cols="30" rows="10" style="display: none;"></textarea>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="w-100 btn btn-primary px-4"
                        onclick="$('#whatsapp-share-form').submit()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-whatsapp" viewBox="0 0 16 16">
                            <path
                                d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
                        </svg>
                        Share on WhatsApp</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        $(function() {
            $("#inputSearchYuvak").on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("ul.birthday-yuvak-list > li").each(function() {
                    if ($(this).data('yuvakname').toLowerCase().search(value) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
            $("#inputSearchYuvak").on('search', function() {
                if ($(this).val().length <= 0) {
                    $("ul.birthday-yuvak-list > li").show();
                }
            });

            $('body').on('hidden.bs.modal', '#modal-member-view', function() {
                $(this).find('.modal-content').html(`<div class="text-center p-5">
                    <i class="text-muted fas fa-spinner fa-spin" style="font-size: 25px;"></i>
                </div>`);
            });


            $(".btn-show-checkbox").on('click', function() {
                $(".member_checkbox_wrap").toggleClass("d-none");
            });
            $(".nav.nav-tabs .nav-link").on('click', function() {
                $(".member_checkbox_wrap .icheck-primary input[type='checkbox']").prop("checked", false);
            });

            $('body').on('show.bs.modal', '#modal-share', function() {
                setShareContent();
            });
            $('body').on('click', '.inputShareQ', function() {
                setShareContent();
            });

            function setShareContent() {
                //console.log(123);
                var share_text = 'List of members who\'s having birthday on ';
                share_text += $('.nav.nav-tabs .nav-item .nav-link.active').data('message-label') + ': ';
                share_text += "\n";

                var $checkboxes = $(
                    '.tab-pane.show.active .member_checkbox_wrap .icheck-primary input[type="checkbox"]');
                if ($checkboxes.filter(':checked').length > 0) {
                    $checkboxes.filter(':checked').each(function(index, currentElement) {
                        var name = $(this).data('member_name');
                        var mobile = $(this).data('mobile');
                        var attending_sabha = $(this).data('attending_sabha');
                        var zone_name = $(this).data('zone_name');
                        var sabha_name = $(this).data('sabha_name');


                        share_text += name + ' ' + mobile;
                        if ($("#shareAttendingsabhaQ:checked").length) {
                            share_text += ' | ' + attending_sabha;
                        }
                        if ($("#shareSabhaQ:checked").length) {
                            share_text += ' | ' + sabha_name;
                        }
                        if ($("#shareZoneQ:checked").length) {
                            share_text += ' | ' + zone_name;
                        }
                        share_text += "\n";

                    });

                } else {
                    $checkboxes.each(function(index, currentElement) {
                        var name = $(this).data('member_name');
                        var mobile = $(this).data('mobile');
                        var attending_sabha = $(this).data('attending_sabha');
                        var zone_name = $(this).data('zone_name');
                        var sabha_name = $(this).data('sabha_name');

                        share_text += name + ' ' + mobile;
                        if ($("#shareAttendingsabhaQ:checked").length) {
                            share_text += ' | ' + attending_sabha;
                        }
                        if ($("#shareSabhaQ:checked").length) {
                            share_text += ' | ' + sabha_name;
                        }
                        if ($("#shareZoneQ:checked").length) {
                            share_text += ' | ' + zone_name;
                        }
                        share_text += "\n";
                    });
                }

                $("textarea#whatsapp-text").text(share_text);
            }

        });
    </script>
@endpush
