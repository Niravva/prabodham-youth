@extends('layouts.app')

@section('title')
    {{ $data[$request->id]['name'] }}
@stop

@section('left_header_content')
    {{ $data[$request->id]['name'] }} | Group
@endsection

@section('right_header_content')
@endsection

@section('left_header_back_button')
    @if (in_array(get_current_admin_level(), [
            'Super_Admin',
            'Country_Admin',
            'State_Admin',
            'Pradesh_Admin',
            'Zone_Admin',
            'Sabha_Admin',
        ]))
        <a class="nav-link mr-3 pl-1" href="{{ route('dashboard.group-list', $data[$request->id]['sabha_id']) }}"
            role="button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff"
                class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </a>
    @endif
@endsection

@section('content')
    <div class="row">

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.893 13.393l-1.135-1.135a2.252 2.252 0 01-.421-.585l-1.08-2.16a.414.414 0 00-.663-.107.827.827 0 01-.812.21l-1.273-.363a.89.89 0 00-.738 1.595l.587.39c.59.395.674 1.23.172 1.732l-.2.2c-.212.212-.33.498-.33.796v.41c0 .409-.11.809-.32 1.158l-1.315 2.191a2.11 2.11 0 01-1.81 1.025 1.055 1.055 0 01-1.055-1.055v-1.172c0-.92-.56-1.747-1.414-2.089l-.655-.261a2.25 2.25 0 01-1.383-2.46l.007-.042a2.25 2.25 0 01.29-.787l.09-.15a2.25 2.25 0 012.37-1.048l1.178.236a1.125 1.125 0 001.302-.795l.208-.73a1.125 1.125 0 00-.578-1.315l-.665-.332-.091.091a2.25 2.25 0 01-1.591.659h-.18c-.249 0-.487.1-.662.274a.931.931 0 01-1.458-1.137l1.411-2.353a2.25 2.25 0 00.286-.76m11.928 9.869A9 9 0 008.965 3.525m11.928 9.868A9 9 0 118.965 3.525" />
                    </svg>
                    <span class="ml-2 card-title">{{ $data[$request->id]['sabhaName'] }} | {{ $data[$request->id]['name'] }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <a href="{{ route('dashboard.followupkaryakarta-list', $request->id) }}">
                                <div class="description-block border-right">
                                    <h5 class="description-header">{{ number_format($data['totalFollowUpKaryakarta']) }}
                                    </h5>
                                    <span class="description-text text-muted">Total Followup</span>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <div class="description-block">
                                <h5 class="description-header">{{ number_format($data['pendingAttendance']) }}</h5>
                                <span class="description-text text-muted">Pending Attendance</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                    <span class="ml-2 card-title">Youths</span>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col">
                            <div class="description-block border-right">
                                <h5 class="description-header">{{ number_format($data['youthSummary']['total']) }}</h5>
                                <span class="description-text text-muted">Total</span>
                            </div>
                        </div>

                        <div class="col">
                            <div class="description-block border-right">
                                <h5 class="description-header">{{ number_format($data['youthSummary']['regular']) }}
                                </h5>
                                <span class="description-text text-muted">Regular</span>
                            </div>
                        </div>

                        <div class="col">
                            <div class="description-block border-right">
                                <h5 class="description-header">{{ number_format($data['youthSummary']['irregular']) }}
                                </h5>
                                <span class="description-text text-muted">Irregular</span>
                            </div>
                        </div>

                        <div class="col">
                            <div class="description-block">
                                <h5 class="description-header">{{ number_format($data['youthSummary']['fresh']) }}</h5>
                                <span class="description-text text-muted">Fresh</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.87c1.355 0 2.697.055 4.024.165C17.155 8.51 18 9.473 18 10.608v2.513m-3-4.87v-1.5m-6 1.5v-1.5m12 9.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0L3 16.5m15-3.38a48.474 48.474 0 00-6-.37c-2.032 0-4.034.125-6 .37m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.17c0 .62-.504 1.124-1.125 1.124H4.125A1.125 1.125 0 013 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 016 13.12M12.265 3.11a.375.375 0 11-.53 0L12 2.845l.265.265zm-3 0a.375.375 0 11-.53 0L9 2.845l.265.265zm6 0a.375.375 0 11-.53 0L15 2.845l.265.265z" />
                    </svg>
                    <span class="ml-2 card-title">Birthday</span>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col">
                            <a
                                href="{{ route('dashboard.birthday', ['for' => 'group', 'id' => $request->id, 'type' => 'today']) }}">
                                <div class="description-block border-right">
                                    <h5 class="description-header">{{ number_format($data['todayBirthday']) }}</h5>
                                    <span class="description-text text-muted">Today</span>
                                </div>
                            </a>
                        </div>

                        <div class="col">
                            <a
                                href="{{ route('dashboard.birthday', ['for' => 'group', 'id' => $request->id, 'type' => 'tomorrow']) }}">
                                <div class="description-block border-right">
                                    <h5 class="description-header">{{ number_format($data['tomorrowBirthday']) }}</h5>
                                    <span class="description-text text-muted">Tomorrow</span>
                                </div>
                            </a>
                        </div>

                        <div class="col">
                            <a
                                href="{{ route('dashboard.birthday', ['for' => 'group', 'id' => $request->id, 'type' => 'thisweek']) }}">
                                <div class="description-block border-right">
                                    <h5 class="description-header">{{ number_format($data['thisWeekBirthday']) }}</h5>
                                    <span class="description-text text-muted">This Week</span>
                                </div>
                            </a>
                        </div>

                        <div class="col">
                            <a
                                href="{{ route('dashboard.birthday', ['for' => 'group', 'id' => $request->id, 'type' => 'thismonth']) }}">
                                <div class="description-block">
                                    <h5 class="description-header">{{ number_format($data['thisMonthBirthday']) }}</h5>
                                    <span class="description-text text-muted">This Month</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                    </svg>
                    <span class="ml-2 card-title">New Joinee <small class="text-muted">(Average)</small></span>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col">
                            <div class="description-block border-right">
                                <h5 class="description-header">{{ number_format($data['weeklyNewJoinee']) }}</h5>
                                <span class="description-text text-muted">Weekly</span>
                            </div>
                        </div>

                        <div class="col">
                            <div class="description-block border-right">
                                <h5 class="description-header">{{ number_format($data['monthlyNewJoinee']) }}</h5>
                                <span class="description-text text-muted">Monthly</span>
                            </div>
                        </div>

                        <div class="col">
                            <div class="description-block border-right">
                                <h5 class="description-header">{{ number_format($data['quartelyNewJoinee']) }}</h5>
                                <span class="description-text text-muted">Quartely</span>
                            </div>
                        </div>

                        <div class="col">
                            <div class="description-block">
                                <h5 class="description-header">{{ number_format($data['yearlyNewJoinee']) }}</h5>
                                <span class="description-text text-muted">Yearly</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
