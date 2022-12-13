<?php

namespace Botble\webrobotdashboard\Tables;
use BaseHelper;
use Botble\Member\Repositories\Interfaces\TaskInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TaskTable extends TableAbstract
{
    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * MemberTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param TaskInterface $taskRepository
     * @param ProjectInterface $projectRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator,ProjectRepositiry $projectRepository, TaskInterface $taskRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $taskRepository;
        $this->projectRepository = $projectRepository;

        if (!Auth::user()->hasAnyPermission(['task.edit', 'task.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('project_id', function ($item) {
                return $item->project && $item->project->name ? BaseHelper::clean($item->project->name) : '&mdash;';
            })
            ->editColumn('date', function ($item) {
                if (!Auth::user()->hasPermission('task.edit')) {
                    return BaseHelper::clean($item->name);
                }
                return Html::link(route('task.edit', $item->id), BaseHelper::clean($item->date));
            })
            ->editColumn('quantity', function ($item) {
                return BaseHelper::clean($item->quantity);
            })
            ->editColumn('dataset', function ($item) {
                return BaseHelper::clean($item->dataset);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('task.edit', 'task.destroy', $item);
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()->select([
            'id',
            'date',
            'quantity',
            'dataset',
            'project_id'
        ]);
        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns(): array
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'date' => [
                'title' => trans('plugins/webrobotdashboard::task.date'),
                'class' => 'text-start',
            ],
            'quantity' => [
                'title' => trans('plugins/webrobotdashboard::task.quantity'),
                'class' => 'text-start',
            ],
            'dataset' => [
                'title' => trans('plugins/webrobotdashboard::task.dataset'),
                'class' => 'text-start',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons(): array
    {
        return $this->addCreateButton(route('task.create'), 'task.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('task.deletes'), 'task.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'date' => [
                'title' => trans('plugins/webrobotdashboard::task.date'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'quantity' => [
                'title' => trans('plugins/webrobotdashboard::task.quantity'),
                'width' => '100px',
                'class' => 'text-center',
            ],
            'dataset' => [
                'title' => trans('plugins/webrobotdashboard::task.dataset'),
                'width' => '100px',
                'class' => 'text-center',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],
        ];
    }
}
