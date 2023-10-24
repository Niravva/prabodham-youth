@extends('layouts.app')

@section('title')
    Zones
@stop

@section('left_header_content')
    Zones
@endsection

@section('right_header_content')
@endsection

@section('left_header_back_button')
    <a class="nav-link mr-3 pl-1" href="{{ route('dashboard') }}" role="button">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
    </a>
@endsection

@section('content')
    <div class="row" style="margin-top: -.5rem;">
        <div class="col-sm-12 p-0">
            <ul class="list">
                <?php $i = 1; ?>
                @foreach ($data as $key => $item)
                    <li>
                        <div class="col-sm-12">
                            <a href="{{ route('dashboard.zone-detail', $item['id']) }}"
                                class="row justify-content-between align-items-center">
                                <div class="col-11">
                                    <div>
                                        <strong>{{ $item['name'] }}</strong>
                                    </div>
                                    <div class="d-flex text-muted">
                                        <div class="border-right pr-2">
                                            <small>
                                                TOTAL: <strong>{{ $item['total_youth'] }}</strong>
                                            </small>
                                        </div>
                                        <div class="border-right pl-2 pr-2">
                                            <small>
                                                REGULAR: <strong>{{ $item['regular_youth'] }}</strong>
                                            </small>
                                        </div>
                                        <div class="pl-2">
                                            <small>IRREGULAR: <strong>{{ $item['irregular_youth'] }}</strong></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
