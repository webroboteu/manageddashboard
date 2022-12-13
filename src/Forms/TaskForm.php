<?php
namespace Botble\webrobotdashboard\Forms;
use Assets;
use BaseHelper;
use Botble\Base\Forms\FormAbstract;
use Botble\webrobotdashboard\Http\Requests\TaskCreateRequest;
use Botble\webrobotdashboard\Models\Task;
use Carbon\Carbon;
use Botble\webrobotdashboard\Repositories\Interfaces\ProjectInterface;
use Botble\webrobotdashboard\Repositories\Interfaces\TaskInterface;
class TaskForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        Assets::addScriptsDirectly(['/vendor/core/plugins/webrobotdashboard/js/task-admin.js']);
        $allProjects = [];
        $allProjects = app(ProjectInterface::class)
            ->getModel()
            ->pluck('id')
            ->all();
        $this
            ->setupModel(new Task())
            ->setValidatorClass(TaskCreateRequest::class)
            ->withCustomFields()
            ->add('project_id', 'customSelect', [
                'label' => trans('plugins/webrobotdashboard::task.project_id'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'class' => 'select-search-full',
                ],
                'choices' => $allProjects,
            ])
            ->add('date', 'text', [
                'label' => trans('plugins/webrobotdashboard::task.date'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('plugins/webrobotdashboard::forms.task_placeholder'),
                    'data-counter' => 120,
                ],
            ])
          
            ->add('quantity', 'text', [
                'label' => trans('plugins/webrobotdashboard::task.quantity'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('plugins/webrobotdashboard::forms.quantity_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ->add('dataset', 'text', [
                'label' => trans('plugins/webrobotdashboard::task.dataset'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('plugins/webrobotdashboard::forms.dataset_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ;
    }
}
