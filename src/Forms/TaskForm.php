<?php
namespace Botble\webrobotdashboard\Forms;
use Assets;
use BaseHelper;
use Botble\Base\Forms\FormAbstract;
use Botble\webrobotdashboard\Http\Requests\TaskCreateRequest;
use Botble\webrobotdashboard\Models\Task;
use Carbon\Carbon;

class TaskForm extends FormAbstract
{
    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        Assets::addScriptsDirectly(['/vendor/core/plugins/webrobot-dashboard/js/task-admin.js']);
        $this
            ->setupModel(new Task())
            ->setValidatorClass(TaskCreateRequest::class)
            ->withCustomFields()
            ->add('date', 'text', [
                'label' => trans('plugins/webrobot-dashboard::task.date'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('plugins/webrobot-dashboard::forms.task_placeholder'),
                    'data-counter' => 120,
                ],
            ])
          
            ->add('quantity', 'text', [
                'label' => trans('plugins/webrobot-dashboard::task.quantity'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('plugins/webrobot-dashboard::forms.quantity_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ->add('dataset', 'text', [
                'label' => trans('plugins/webrobot-dashboard::task.dataset'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('plugins/webrobot-dashboard::forms.dataset_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ;
    }
}
