<?php

namespace App\Livewire;

use App\Models\EventTask;
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

final class EventTaskTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'event-task-table';

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
        // EventTasks are mostly managed by Sie Acara, but anyone can view them if they have permissions
        return EventTask::query()->with(['event', 'pjUser']);
    }

    public function relationSearch(): array
    {
        return [
            'event' => [
                'nama_event',
            ],
            'pjUser' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('sub_event')
            ->add('kebutuhan_aspek')
            ->add('event_name', fn (EventTask $model) => e($model->event?->nama_event ?? '-'))
            ->add('pj_name', fn (EventTask $model) => e($model->pjUser?->name ?? '-'))
            ->add('status')
            ->add('target_tanggal_formatted', fn (EventTask $model) => Carbon::parse($model->target_tanggal)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('Sub Event', 'sub_event')
                ->searchable()
                ->sortable(),

            Column::make('Aspek', 'kebutuhan_aspek')
                ->searchable()
                ->sortable(),

            Column::make('Event', 'event_name'),

            Column::make('PJ', 'pj_name'),

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

    public function actions(EventTask $row): array
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
