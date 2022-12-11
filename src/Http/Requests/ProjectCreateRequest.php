<?php
namespace Botble\webrobotdashboard\Http\Requests;
use Botble\Support\Http\Requests\Request;
use Botble\webrobotdashboard\Enums\FrequencyEnum;
use Botble\webrobotdashboard\Enums\StatusEnum;
class ProjectCreateRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:120|min:2',
            'description' => 'required|max:120|min:2',
            'frequency' => Rule::in(FrequencyEnum::values()),
            'status' => Rule::in(StatusEnum::values()),
        ];
    }
}
