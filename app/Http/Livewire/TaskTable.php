<?php

namespace App\Http\Livewire;

use App\Entities\Task;
use App\Repositories\TaskRepository;
use Illuminate\Contracts\View\View;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

use function Pest\Laravel\instance;

class TaskTable extends DataTableComponent
{
    public const PRIMARY_KEY = 'id';
    public const TABLE_ATTRS = [
        'class' => 'max-w-full'
    ];
    public const TABLE_TH_ATTRS = [
        'default' => false,
        'class' => 'place-content-center p-2 text-left text-xs font-medium whitespace-nowrap text-gray-500 uppercase tracking-wider dark:bg-gray-800 dark:text-gray-400',
    ];
    public const TABLE_TD_ATTRS = [
        'default' => false,
        'class' => 'p-2 whitespace-nowrap text-sm font-medium dark:text-white',
    ];
    public const TABLE_WRAPPER_ATTRS = [
        'default' => false,
        'class' => 'shadow border-b border-gray-200 dark:border-gray-700 sm:rounded-lg',
    ];
    public const CONFIGURABLE_AREAS_VIEWS = [
        'toolbar-left-start' => ['components.create-button', null],
    ];

    public $model = Task::class;
    public $editButtonParams = [
        'model' => Task::class,
        'inputsView' => 'tasks.inputs',
        'inputRules' => [
            'model.title' => 'required|string',
            'model.due' => 'required|date_format:' . Task::DUE_DATETIME_FORMAT,
        ],
        'title' => 'Just edit that',
        'confirmBtnLabel' => 'Update',
        'confirmBtnColor' => 'green',
        'confirmAction' => [
            self::class,
            'save',
            null,
            'refreshDatatable'
        ],
    ];
    public $createButtonParams = [
        'title' => 'Create a new one',
        'confirmBtnLabel' => 'Create',
    ];
    public $deleteButtonParams = [
        'title' => 'Are you sure?',
        'description' => 'Do you really sure that you want to exclude this register?',
        'confirmBtnLabel' => 'Delete',
        'confirmBtnColor' => 'red' ,
        'confirmAction' => [
            self::class,
            'delete',
            null,
            'refreshDatatable'
        ],
    ];
    protected TaskRepository $repository;

    public function configure(): void
    {
        $this->repository = app(abstract:TaskRepository::class);
        $areas = self::CONFIGURABLE_AREAS_VIEWS;
        $areas['toolbar-left-start'][1]['createButtonParams'] = array_merge(
            $this->editButtonParams,
            $this->createButtonParams
        );
        $this->setPrimaryKey(key:self::PRIMARY_KEY);
        $this->setTableAttributes(self::TABLE_ATTRS);
        $this->setThAttributes(callback:static fn () => self::TABLE_TH_ATTRS);
        $this->setTdAttributes(callback:static fn () => self::TABLE_TD_ATTRS);
        $this->setTableWrapperAttributes(attributes:self::TABLE_WRAPPER_ATTRS);
        $this->setConfigurableAreas($areas);
        $this->setColumnSelectDisabled();
    }

    public function columns(): array
    {
        return [
            Column::make(title:__(key:'Id'), from:'id')
                ->sortable()
                ->searchable()
                ->collapseOnMobile(),
            Column::make(title:__(key:'Creator'), from:'creator.name')
                ->sortable()
                ->searchable()
                ->collapseOnMobile(),
            Column::make(title:__(key:'Title'), from:'title')
                ->searchable()
                ->sortable(),
            Column::make(title:__(key:'Due'), from:'due')
                ->searchable()
                ->sortable()
                ->collapseOnMobile(),
            Column::make(title:__(key:'Status'), from:'status')
                ->searchable()
                ->sortable(),
            Column::make(title:__(key:'Created at'), from:'created_at')
                ->searchable()
                ->sortable()
                ->collapseOnMobile(),
            Column::make(title:__(key:'Updated at'), from:'updated_at')
                ->searchable()
                ->sortable()
                ->collapseOnMobile(),
            Column::make(title:__(key:'Actions'), from:'id')
                ->format(callable:fn ($val) => $this->actionButtonsParams(id:$val))
                ->collapseOnMobile(),
        ];
    }

    public function getRows()
    {
        $this->setBuilder($this->repository->getBuilder());
        $this->baseQuery();
        return $this->executeQuery();
    }

    public function save(Task $task): bool
    {
        $arr = $task->toArray();
        return $this->repository->updateOrCreate(
            attributes:array_keys($arr),
            values:array_values($arr)
        );
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }

    protected function actionButtonsParams(int $id): View
    {
        $data = [
            'id' => $id,
            'editButtonParams' => $this->editButtonParams,
            'deleteButtonParams' => $this->deleteButtonParams,
        ];
        /**
         * @see \App\Http\Livewire\Modals\Modal::$confirmAction[$class, $action, $model, $event]
         */
        $data['deleteButtonParams']['confirmAction'][2] = $id;
        $data['deleteButtonParams']['title'] = __($data['deleteButtonParams']['title']);
        $data['deleteButtonParams']['description'] = __($data['deleteButtonParams']['description']);
        $data['deleteButtonParams']['confirmBtnLabel'] = __($data['deleteButtonParams']['confirmBtnLabel']);

        $data['editButtonParams']['confirmAction'][2] = $id;
        $data['editButtonParams']['title'] = __($data['editButtonParams']['title']);
        $data['editButtonParams']['confirmBtnLabel'] = __($data['editButtonParams']['confirmBtnLabel']);
        $data['editButtonParams']['model'] = Task::findOrFail($id);

        return view('components.action-buttons', $data);
    }
}
