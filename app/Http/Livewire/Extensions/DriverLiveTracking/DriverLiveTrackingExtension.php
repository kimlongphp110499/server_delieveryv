<?php

namespace App\Http\Livewire\Extensions\DriverLiveTracking;

use App\Http\Livewire\Extensions\BaseExtensionComponent;
use App\Models\User;
use App\Traits\FirebaseAuthTrait;

class DriverLiveTrackingExtension extends BaseExtensionComponent
{

    use FirebaseAuthTrait;

    public $showCreate = false;

    protected $listeners = [
        'showDriverLiveTracking' => 'showDriverLiveTracking',
        'showExtensions' => 'showExtensions',
        'loadDrivers' => 'loadDrivers',
        'closeDialog' => 'closeDialog',
        'dismissModal' => 'closeDialog',
    ];

    public function render()
    {
        return view('livewire.extensions.driver_live_tracking.index');
    }

    public function showExtensions()
    {
        $this->showView = false;
        $this->emitUp('showExtensions');
    }

    public function showDriverLiveTracking()
    {
        $this->show();
        $markerIcon = asset('images/extensions/driver_tracking_delivery_boy.png');
        $this->emit("loadMap", $markerIcon);
        $data[] = setting('apiKey');
        $data[] = setting('projectId');
        $data[] = setting('messagingSenderId');
        $data[] = setting('appId');
        $data[] = $this->firebaseAuthCustomToken(\Auth::user());
        //
        $this->emit("authenticateUser", $data);
    }

    public function closeDialog()
    {
        $this->showCreate = false;
    }



    //
    public function showAllDriversOnMap()
    {
        $drivers = User::whereHas('roles', function ($query) {
            return $query->where('name', "driver");
        })->get()->toArray();
        //
        $this->emit("loadDriversOnMap", $drivers);
    }

    public function showOnlineDriversOnMap()
    {
        $drivers = User::whereHas('roles', function ($query) {
            return $query->where('name', "driver");
        })->where('is_online', 1)->get()->toArray();
        //
        $this->emit("loadDriversOnMap", $drivers);
    }

    public function showOfflineDriversOnMap()
    {
        $drivers = User::whereHas('roles', function ($query) {
            return $query->where('name', "driver");
        })->where('is_online', 0)->get()->toArray();
        //
        $this->emit("loadDriversOnMap", $drivers);
    }
    public function loadDrivers()
    {
        $drivers = User::whereHas('roles', function ($query) {
            return $query->where('name', "driver");
        })->get()->toArray();

        $this->emit("loadDriversOnMap", $drivers);
        $this->closeDialog();
    }




    //
    public function firebaseAuthCustomToken($user)
    {

        $authToken = session('fbCustomToken');
        $authTokenExpiry = session('fbCustomTokenExpiry');

        if (empty($authToken) || empty($authTokenExpiry) || $authTokenExpiry < time()) {
            $uId = "user_id_" . $user->id . "";
            $authToken = $this->getFirebaseAuth()->createCustomToken($uId)->toString();
            session(['fbCustomToken' => $authToken]);
        }

        return $authToken;
    }
}
