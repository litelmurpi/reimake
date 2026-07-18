<?php

namespace App\Livewire;

use App\Models\AdminTask;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class AdminTaskTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'admin-task-table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
            PowerGrid::responsive(),
        ];
    }

    public function datasource(): Builder
    {
        // For BPH, they can see all. For others, only their divisi?
        // Let's defer to policy via the component using it, or just scope here.
        if (auth()->user()->hasRole('bph')) {
            return AdminTask::query()->with('divisi');
        }
        
        // Members see their own divisi tasks
        return AdminTask::query()
            ->with('divisi')
            ->whereIn('divisi_id', auth()->user()->divisis->pluck('id'));
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('nama_tugas')
            ->add('divisi_name', fn (AdminTask $model) => e($model->divisi->nama_divisi))
            ->add('status')
            ->add('target_tanggal_formatted', fn (AdminTask $model) => Carbon::parse($model->target_tanggal)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Nama Tugas', 'nama_tugas')
                ->searchable()
                ->sortable(),

            Column::make('Divisi', 'divisi_name'),

            Column::make('Status', 'status')
                ->sortable(),

            Column::make('Target', 'target_tanggal_formatted', 'target_tanggal')
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('status', 'status')
                ->dataSource(collect([
                    ['status' => 'Belum Mulai', 'label' => 'Belum Mulai'],
                    ['status' => 'Proses', 'label' => 'Proses'],
                    ['status' => 'Selesai', 'label' => 'Selesai'],
                    ['status' => 'Batal', 'label' => 'Batal'],
                ]))
                ->optionValue('status')
                ->optionLabel('label'),
        ];
    }

    public function actions(AdminTask $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('bg-pale-blue text-pale-bluetext px-2 py-1 rounded text-sm hover:bg-blue-200 transition-colors')
                ->dispatch('edit', ['rowId' => $row->id])
        ];
    }
}
