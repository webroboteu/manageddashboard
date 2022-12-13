<?php
namespace Botble\webrobotdashboard\Forms;
use Assets;
use BaseHelper;
use Botble\Base\Forms\FormAbstract;
use Botble\webrobotdashboard\Http\Requests\ProjectCreateRequest;
use Botble\webrobotdashboard\Models\Project;
use Carbon\Carbon;
use Botble\webrobotdashboard\enums\StatusEnum;
use Botble\webrobotdashboard\enums\FrequencyEnum;

class ProjectForm extends FormAbstract
{
    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        Assets::addScriptsDirectly(['/vendor/core/plugins/webrobotdashboard/js/project-admin.js']);
        $this
            ->setupModel(new Project())
            ->setValidatorClass(ProjectCreateRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('plugins/webrobotdashboard::project.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
          
            ->add('description', 'textarea', [
                'label' => trans('plugins/webrobotdashboard::project.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ->add('status', 'customSelect', [
                'label' => trans('plugins/webrobotdashboard::project.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => StatusEnum::labels(),
            ])
            ->add('frequency', 'customSelect', [
                'label' => trans('plugins/webrobotdashboard::project.frequency'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => FrequencyEnum::labels(),
            ])
            ;
    }
}
