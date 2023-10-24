<div class="modal-header">
    <h4 class="modal-title">{{ $group->name }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg></span>
    </button>
</div>
<div class="modal-body">

    <h6>Group Admins</h6>
    <table class="table">
        <tr>
            <td>
                {!! get_group_admin_name($group->id) !!}
            </td>
        </tr>
    </table>

    <h6>Followup Admins</h6>
    <table class="table">
        <tr>
            <td>
                {!! get_group_followup_admin_name($group->id) !!}
            </td>
        </tr>
    </table>
</div>
<div class="modal-footer justify-content-between">
    <button id="btn-cancel" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
