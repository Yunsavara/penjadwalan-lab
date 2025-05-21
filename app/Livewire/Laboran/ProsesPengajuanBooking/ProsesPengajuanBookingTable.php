<?php

namespace App\Livewire\Laboran\ProsesPengajuanBooking;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ProsesPengajuanBookingTable extends PowerGridComponent
{
    public string $tableName = 'pengajuan_bookings';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return DB::table('pengajuan_bookings')
            ->leftJoin('lokasis', 'pengajuan_bookings.lokasi_id', '=', 'lokasis.id')
            ->leftJoin('users', 'pengajuan_bookings.user_id', '=', 'users.id')
            ->select(
                'pengajuan_bookings.*',
                'lokasis.nama_lokasi as nama_lokasi',
                'users.nama_pengguna as nama_pengguna'
            );
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('kode_booking')
            ->add('status_pengajuan_booking')
            ->add('keperluan_pengajuan_booking')
            ->add('balasan_pengajuan_booking', function ($dish) { return $dish->balasan_pengajuan_booking ?: '-';})
            ->add('mode_tanggal_pengajuan')
            ->add('nama_lokasi')
            ->add('nama_pengguna')
            ->add('created_at')
            ->add('created_at_formatted', function ($dish) {
                return Carbon::parse($dish->created_at)->locale('id')->translatedFormat('d F Y H:i'); 
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Kode booking', 'kode_booking')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status_pengajuan_booking')
                ->sortable()
                ->searchable(),

            Column::make('Keperluan', 'keperluan_pengajuan_booking')
                ->sortable()
                ->searchable(),

            Column::make('Balasan', 'balasan_pengajuan_booking')
                ->sortable()
                ->searchable(),

            Column::make('Mode', 'mode_tanggal_pengajuan')
                ->sortable()
                ->searchable(),

            Column::make('Lokasi', 'nama_lokasi'),
            Column::make('Oleh', 'nama_pengguna'),

            Column::make('Created at', 'created_at_formatted')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions($row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('btn btn-primary')
                ->dispatch('edit', ['rowId' => $row->id]),
            
            Button::add('detail')
                ->slot('Detail')
                ->id()
                ->class('btn btn-info')
                ->dispatch('detailPengajuanBookingModal', ['rowId' => $row->id]),
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
