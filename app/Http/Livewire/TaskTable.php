<?php

namespace App\Http\Livewire;

use App\Models\Task;
use function PHPUnit\Framework\callback;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaskTable extends DataTableComponent
{
    const PRIMARY_KEY = 'id';
    const TABLE_TH_ATTRS = [
        'default' => false,
        'class' => 'p-3 text-left text-xs font-medium whitespace-nowrap text-gray-500 uppercase tracking-wider dark:bg-gray-800 dark:text-gray-400',
    ];
    const TABLE_WRAPPER_ATTRS = [
        'default' => false,
        'class' => 'shadow border-b border-gray-200 dark:border-gray-700 sm:rounded-lg',
    ];

    protected $model = Task::class;

    public function configure(): void
    {
        $this->setPrimaryKey(key:self::PRIMARY_KEY);
        $this->setThAttributes(callback:static fn() => self::TABLE_TH_ATTRS);
        $this->setTableWrapperAttributes(attributes:self::TABLE_WRAPPER_ATTRS);
        $this->setSearchDisabled();
        $this->setColumnSelectDisabled();
    }

    public function columns(): array
    {
        return [
            Column::make(title:__(key:'Id'), from:'id')
                ->sortable()
                ->collapseOnMobile(),
            Column::make(title:__(key:'Creator'), from:'creator.name')
                ->sortable()
                ->collapseOnMobile(),
            Column::make(title:__(key:'Title'), from:'title')
                ->sortable(),
            Column::make(title:__(key:'Due'), from:'due')
                ->sortable()
                ->collapseOnMobile(),
            Column::make(title:__(key:'Status'), from:'status')
                ->sortable(),
            Column::make(title:__(key:'Created at'), from:'created_at')
                ->sortable()
                ->collapseOnMobile(),
            Column::make(title:__(key:'Updated at'), from:'updated_at')
                ->sortable()
                ->collapseOnMobile(),
            Column::make(title:__('Action'), from:'id')
                ->format(callable :static function ($row) {
                    return view('components.action-buttons', ['row' => $row]);
                })
                ->view('components.action-buttons')
                ->collapseOnMobile(),
        ];
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}
