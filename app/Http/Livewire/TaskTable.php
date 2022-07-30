<?php

namespace App\Http\Livewire;

use App\Models\Task;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class TaskTable extends DataTableComponent
{
    const PRIMARY_KEY = 'id';
    const TABLE_WRAPPER_ATTRS = [
            'default' => false,
            'class' => 'shadow border-b border-gray-200 dark:border-gray-700 sm:rounded-lg'
    ];
    protected $model = Task::class;

    public function configure(): void
    {
        $this->setPrimaryKey(key: self::PRIMARY_KEY);
        $this->setTableWrapperAttributes([
            'default' => false,
            'class' => 'shadow border-b border-gray-200 dark:border-gray-700 sm:rounded-lg'
        ]);
        $this->setSearchDisabled();
        $this->setColumnSelectDisabled();
    }

    public function columns(): array
    {
        return [
            Column::make(title: __(key: 'Id'), from: 'id')
                ->sortable()
                ->collapseOnMobile(),
            Column::make(title: __(key: 'Creator'), from: 'creator.name')
                ->sortable()
                ->collapseOnMobile(),
            Column::make(title: __(key: 'Title'), from: 'title')
                ->sortable(),
            Column::make(title: __(key: 'Due'), from: 'due')
                ->sortable()
                ->collapseOnMobile(),
            Column::make(title: __(key: 'Status'), from: 'status')
                ->sortable(),
            Column::make(title: __(key: 'Created at'), from: 'created_at')
                ->sortable()
                ->collapseOnMobile(),
            Column::make(title: __(key: 'Updated at'), from: 'updated_at')
                ->sortable()
                ->collapseOnMobile(),
        ];
    }
}
