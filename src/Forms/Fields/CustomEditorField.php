<?php

namespace Botble\webrobotdashboard\Forms\Fields;

use BaseHelper;
use Botble\Base\Supports\Editor;
use Illuminate\Support\Arr;
use Kris\LaravelFormBuilder\Fields\FormField;

class CustomEditorField extends FormField
{
    /**
     * @return string
     */
    protected function getTemplate(): string
    {
        return 'plugins/webrobotdashboard::forms.fields.custom-editor';
    }

    /**
     * @param array $options
     * @param bool $showLabel
     * @param bool $showField
     * @param bool $showError
     * @return string
     */
    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true): string
    {
        (new Editor())->registerAssets();

        $options['attr'] = Arr::set($options['attr'], 'class', Arr::get($options['attr'], 'class') . 'form-control editor-' . BaseHelper::getRichEditor());

        return parent::render($options, $showLabel, $showField, $showError);
    }
}
