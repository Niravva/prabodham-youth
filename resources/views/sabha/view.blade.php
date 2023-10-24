<div class="modal-header">
    <h4 class="modal-title">Sabha Details</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg></span>
    </button>
</div>
<div class="modal-body p-0">
    <table class="table table-bordered table-striped mb-0">
        <tr>
            <th>Code:</th>
            <td>{{ $sabha->sabha_code }}</td>
        </tr>
        <tr>
            <th>Name:</th>
            <td>
                {{ $sabha->name }}<br>
                <small>{{ $pradesh->name }} / {{ $zone->name }}</small>
            </td>
        </tr>
        <tr>
            <th>Head:</th>
            <td>
                {!! get_member_fullname($sabha->sabha_head_id, true) !!}
            </td>
        </tr>
        <tr>
            <th>Type:</th>
            <td>{{ $sabha->sabha_type }}</td>
        </tr>
        <tr>
            <th>Occurance:</th>
            <td>{{ $sabha->occurance }}</td>
        </tr>
        <tr>
            <th>Day / Time:</th>
            <td>{{ get_sabha_days()[$sabha->sabha_day] . ' / ' . $sabha->sabha_time }}</td>
        </tr>
        <tr>
            <th>Flat No:</th>
            <td>{{ $sabha->flat_no }}</td>
        </tr>
        <tr>
            <th>Building Name:</th>
            <td>{{ $sabha->building_name }}</td>
        </tr>
        <tr>
            <th>Landmark:</th>
            <td>{{ $sabha->landmark }}</td>
        </tr>
        <tr>
            <th>Street Name:</th>
            <td>{{ $sabha->street_name }}</td>
        </tr>
        <tr>
            <th>Postcode:</th>
            <td>{{ $sabha->postcode }}</td>
        </tr>
        <tr>
            <th>City:</th>
            <td>{{ $city->name }}</td>
        </tr>
        <tr>
            <th>State:</th>
            <td>{{ $state->name }}</td>
        </tr>
        <tr>
            <th>Country:</th>
            <td>{{ $country->name }}</td>
        </tr>
        <tr>
            <th>Location:</th>
            <td>
                {{ $sabha->latitude }} / {{ $sabha->longitude }}
            </td>
        </tr>
    </table>
</div>
<div class="modal-footer justify-content-between">
    <button id="btn-cancel" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
