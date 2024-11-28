<?php

namespace App\Http\Livewire\Extensions\DriverLiveTracking;

use App\Http\Livewire\Tables\BaseDataTableComponent;
use App\Models\User;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DriversTableExtension extends BaseDataTableComponent
{

    public bool $columnSelect = false;
    public array $perPageAccepted = [8, 10];
    public array $bulkActions = [
        'showDriversOnMap' => 'Show On Map',
    ];

    public function query()
    {

        return User::with('roles')->whereHas('roles', function ($query) {
            return $query->where('name', "driver");
        });
    }
    public function columns(): array
    {
        return [
            Column::make(__('Name'), 'name')->searchable()->sortable(),
        ];
    }


    public function showDriversOnMap()
    {

        if (count($this->selectedKeys)) {
            // Do something with the selected rows
            $drivers = User::whereIn('id', $this->selectedKeys)->get()->toArray();
            $this->emit("closeDialog");
            $this->emit("loadDriversOnMap", $drivers);
        }
    }
}
