<?php

namespace App\Livewire\Pengguna\Booking;

use App\Models\PengajuanBooking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class PengajuanBookingTable extends PowerGridComponent
{
    public string $tableName = 'pengajuan_bookings';

    public bool $deferLoading = true;
    // public string $loadingComponent = 'components.my-custom-loading';

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
        return PengajuanBooking::query()->where('user_id', Auth::id())->with('lokasi');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('kode_booking')
            ->add('status_pengajuan_booking')
            ->add('keperluan_pengajuan_booking')
            ->add('nama_lokasi', fn (PengajuanBooking $model) => optional($model->lokasi)->nama_lokasi)
            ->add('created_at')
            ->add('created_at_formatted', fn (PengajuanBooking $model) => Carbon::parse($model->created_at)->locale('id')->translatedFormat('d F Y H:i'));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Kode Booking', 'kode_booking')
                ->searchable()
                ->sortable(),
            
            Column::make('Status', 'status_pengajuan_booking')
                ->searchable()
                ->sortable(),

            Column::make('Keperluan', 'keperluan_pengajuan_booking'),

            Column::make('Lokasi', 'nama_lokasi'),

            Column::make('Created at', 'created_at')
                ->hidden(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('kode_booking'),
            Filter::datepicker('created_at_formatted', 'created_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(PengajuanBooking $row): array
    {
        $actions = [
            Button::add('detail')
                ->slot('Detail')
                ->id()
                ->class('btn btn-info')
                ->dispatch('detailPengajuanBookingModal', ['rowId' => $row->id])
        ];

        if ($row->status_pengajuan_booking === 'menunggu') {
            $actions[] = Button::add('edit')
                ->slot('Ubah')
                ->id()
                ->class('btn btn-primary')
                ->dispatch('openModalEdit', ['rowId' => $row->id]);
        }

        return $actions;
    }

    public function noDataLabel(): string|View
    { 
        return 'Tidak ada data yang ditemukan.';
        // return view('dishes.no-data');
    }

    /*
    public function actionRules(PengajuanBooking $row): array
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
