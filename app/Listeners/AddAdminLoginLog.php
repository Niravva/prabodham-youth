<?php

namespace App\Listeners;

use App\Events\AdminLoginEvent;
use App\Models\AdminLoginLog;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use InteractionDesignFoundation\GeoIP\Facades\GeoIP;

class AddAdminLoginLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AdminLoginEvent  $event
     * @return void
     */
    public function handle(AdminLoginEvent $event)
    {
        //
        $userDetails = $event->user;

        $request = Request();
        $inputs = [
            "country_id" => $userDetails->country_id,
            "state_id" => $userDetails->state_id,
            "city_id" => $userDetails->city_id,
            "pradesh_id" => $userDetails->pradesh_id,
            "zone_id" => $userDetails->zone_id,
            "sabha_id" => $userDetails->sabha_id,
            "group_id" => $userDetails->group_id,
            "admin_id" => $userDetails->id,
            "location" => serialize(optional(geoip()->getLocation($request->ip()))->toArray()),
            "action_type" => $userDetails->event_type,
            "ip_address" => $request->ip(),
            "user_agent" => $request->userAgent(),
        ];
        $carbonDateTime = Carbon::now();
        $inputs['created_at'] = $carbonDateTime->toDateTimeString();
        $inputs['updated_at'] = $carbonDateTime->toDateTimeString();
        
        AdminLoginLog::create($inputs);
    }
}
