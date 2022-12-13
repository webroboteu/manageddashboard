<?php

namespace Botble\webrobotdashboard\Tables;
use BaseHelper;
use Botble\webrobotdashboard\Repositories\Interfaces\ProjectInterface;
use Botble\webrobotdashboard\Repositories\Interfaces\MemberInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ProjectTable extends TableAbstract
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
     * @param MemberInterface $memberRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ProjectInterface $projectRepository, MemberInterface $memberRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $projectRepository;
        $this->memberRepository= $memberRepository;

        if (!Auth::user()->hasAnyPermission(['project.edit', 'project.destroy'])) {
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
            ->editColumn('member_id', function ($item) {
                return $item->member && $item->member->name ? BaseHelper::clean($item->member->name) : '&mdash;';
            })
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('project.edit')) {
                    return BaseHelper::clean($item->name);
                }
                return Html::link(route('project.edit', $item->id), BaseHelper::clean($item->name));
            })
            ->editColumn('description', function ($item) {
                return BaseHelper::clean($item->description);
            })
            ->editColumn('status', function ($item) {
                if ($this->request()->input('action') === 'excel') {
                    return $item->status->getValue();
                }
                return BaseHelper::clean($item->status->toHtml());
            })
            ->editColumn('frequency', function ($item) {
                if ($this->request()->input('action') === 'excel') {
                    return $item->frequency->getValue();
                }
                return BaseHelper::clean($item->frequency->toHtml());
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('project.edit', 'project.destroy', $item);
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
            'member_id',
            'name',
            'description',
            'frequency',
            'status',
            'created_at',
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
            'member_id' => [
                'title' => trans('plugins/webrobotdashboard::member'),
                'width' => '150px',
                'class' => 'no-sort text-center',
                'orderable' => false,
            ],
            'name' => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'description' => [
                'title' => trans('plugins/webrobotdashboard::project.description'),
                'class' => 'text-start',
            ],
            'status' => [
                'title' => trans('plugins/webrobotdashboard::project.status'),
                'width' => '100px',
                'class' => 'text-center',
            ],
            'frequency' => [
                'title' => trans('plugins/webrobotdashboard::project.frequency'),
                'width' => '100px',
                'class' => 'text-center',
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
        return $this->addCreateButton(route('project.create'), 'project.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('project.deletes'), 'project.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title' => trans('plugins/webrobotdashboard::project.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'description' => [
                'title' => trans('plugins/webrobotdashboard::project.description'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'status' => [
                'title' => trans('plugins/webrobotdashboard::project.status'),
                'type' => 'customSelect',
                'choices' => StatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', StatusEnum::values()),
            ],
            'frequency' => [
                'title' => trans('plugins/webrobotdashboard::project.frequency'),
                'type' => 'customSelect',
                'choices' => FrequencyEnum::labels(),
                'validate' => 'required|in:' . implode(',', FrequencyEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'date',
            ],
        ];
    }
}
