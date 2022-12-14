<?php
namespace Botble\webrobotdashboard\Tables;
use BaseHelper;
use Botble\webrobotdashboard\Repositories\Interfaces\TaskInterface;
use Botble\webrobotdashboard\Repositories\Interfaces\ProjectInterface;
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
     * @param ProjectInterface $projectRepository
     * @param TaskInterface $taskRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator,ProjectInterface $projectRepository, TaskInterface $taskRepository)
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
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('id', function ($item) {
                return BaseHelper::clean($item->id);
            })
            ->editColumn('project_id', function ($item) {
                return  BaseHelper::clean($item->project_id);
            })
            ->editColumn('date', function ($item) {
                return BaseHelper::clean($item->date);
            })
            ->editColumn('quantity', function ($item) {
                return BaseHelper::clean($item->quantity);
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
            'sites',
            'project_id'
        ])
        ;
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
            'project_id' => [
                'title' => trans('plugins/webrobotdashboard::task.project_id'),
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
            'sites' => [
                'title' => trans('plugins/webrobotdashboard::task.sites'),
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
            'sites' => [
                'title' => trans('plugins/webrobotdashboard::task.sites'),
                'class' => 'text-start',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],
        ];
    }
}
