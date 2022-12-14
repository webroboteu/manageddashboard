<?php
namespace Botble\webrobotdashboard\Http\Controllers;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Botble\webrobotdashboard\Forms\TaskForm;
use Botble\webrobotdashboard\Http\Requests\TaskCreateRequest;
use Botble\webrobotdashboard\Http\Requests\TaskEditRequest;
use Botble\webrobotdashboard\Repositories\Interfaces\TaskInterface;
use Botble\webrobotdashboard\Tables\TaskTable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class TaskController extends BaseController
{
    use HasDeleteManyItemsTrait;

    /**
     * @var TaskInterface
     */
    protected $taskRepository;

    /**
     * @param TaskInterface $taskRepository
     */
    public function __construct(TaskInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param TaskTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(TaskTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/webrobotdashboard.menu_name'));

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/webrobotdashboard::task.create'));
        return $formBuilder
            ->create(TaskForm::class)
            ->renderForm();
    }

    /**
     * @param TaskCreateRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(TaskCreateRequest $request, BaseHttpResponse $response)
    {
        $task = $this->taskRepository->getModel();
        $task->fill($request->input());
        if(is_null($task->sites))
            $task->sites = '';
        $task = $this->taskRepository->createOrUpdate($task);
       //event(new CreatedTaskEvent(MEMBER_MODULE_SCREEN_NAME, $request, $task));
        return $response
            ->setPreviousUrl(route('task.index'))
            ->setNextUrl(route('task.edit', $task->id))
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
        $task = $this->taskRepository->findOrFail($id);

        //event(new BeforeTaskEvent($request, $task));

        page_title()->setTitle(trans('plugins/webrobotdashboard.task.edit'));

        return $formBuilder
            ->create(TaskForm::class, ['model' => $task])
            ->renderForm();
    }

    /**
     * @param $id
     * @param TaskEditRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, TaskEditRequest $request, BaseHttpResponse $response)
    { 
        $task = $this->taskRepository->findOrFail($id);
        $task->fill($request->input());
        if(is_null($task->sites))
        $task->sites = '';
        $task = $this->taskRepository->createOrUpdate($task);
        //event(new UpdatedTaskEvent(MEMBER_MODULE_SCREEN_NAME, $request, $task));
        return $response
            ->setPreviousUrl(route('task.index'))
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
            $task = $this->taskRepository->findOrFail($id);
            $this->taskRepository->delete($task);
            //event(new DeletedTaskEvent(MEMBER_MODULE_SCREEN_NAME, $request, $task));
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
        return $this->executeDeleteItems($request, $response, $this->taskRepository, MEMBER_MODULE_SCREEN_NAME);
    }
}
