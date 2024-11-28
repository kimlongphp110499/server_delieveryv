<?php

namespace App\Http\Livewire\Extensions\DriverLiveTracking;

use App\Models\Extension;

class Installer
{

    public function run()
    {

        $driverTrackingCount = Extension::where('action', 'showDriverLiveTracking')->count();
        //
        if($driverTrackingCount > 1){
            Extension::where('action', 'showDriverLiveTracking')->delete();
            $driverTrackingCount = 0;
        }
        if ($driverTrackingCount == 0) {
            \DB::table('extensions')->insert(array(
                0 =>
                array(
                    'name' => 'Driver Live Tracking',
                    'description' => 'Track your drivers location in real time',
                    'action' => 'showDriverLiveTracking',
                    'icon' => 'heroicon-o-location-marker',
                    'component' => 'extensions.driver-live-tracking.driver-live-tracking-extension',
                    'is_active' => true,
                ),
            ));
        }
    }
}
