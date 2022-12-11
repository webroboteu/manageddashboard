<?php
namespace Botble\webrobotdashboard\Http\Controllers;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Botble\webrobotdashboard\Forms\ProjectForm;
use Botble\webrobotdashboard\Http\Requests\ProjectCreateRequest;
use Botble\webrobotdashboard\Repositories\Interfaces\ProjectInterface;
use Botble\webrobotdashboard\Tables\ProjectTable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ProjectController extends BaseController
{
    use HasDeleteManyItemsTrait;

    /**
     * @var ProjectInterface
     */
    protected $projectRepository;

    /**
     * @param ProjectInterface $projectRepository
     */
    public function __construct(ProjectInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @param ProjectTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(ProjectTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/webrobot-dashboard::project.menu_name'));

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/webrobot-dashboard::project.create'));
        return $formBuilder
            ->create(ProjectForm::class)
            ->renderForm();
    }

    /**
     * @param ProjectCreateRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(ProjectCreateRequest $request, BaseHttpResponse $response)
    {
        $project = $this->projectRepository->getModel();
        $project->fill($request->input());
        $project = $this->projectRepository->createOrUpdate($project);
        event(new CreatedProjectEvent(MEMBER_MODULE_SCREEN_NAME, $request, $project));
        return $response
            ->setPreviousUrl(route('project.index'))
            ->setNextUrl(route('project.edit', $project->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $project = $this->projectRepository->findOrFail($id);

        event(new BeforeEditProjectEvent($request, $project));

        page_title()->setTitle(trans('plugins/webrobot-dashboard::project.edit'));

        return $formBuilder
            ->create(ProjectForm::class, ['model' => $project])
            ->renderForm();
    }

    /**
     * @param $id
     * @param ProjectEditRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, ProjectEditRequest $request, BaseHttpResponse $response)
    {
        $project = $this->projectRepository->findOrFail($id);
        $project->fill($request);
        $project = $this->projectRepository->createOrUpdate($project);
        event(new UpdatedProjectEvent(MEMBER_MODULE_SCREEN_NAME, $request, $project));
        return $response
            ->setPreviousUrl(route('project.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $project = $this->projectRepository->findOrFail($id);
            $this->projectRepository->delete($project);
            event(new DeletedProjectEvent(MEMBER_MODULE_SCREEN_NAME, $request, $project));
            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->projectRepository, MEMBER_MODULE_SCREEN_NAME);
    }
}
