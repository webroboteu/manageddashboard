<?php
namespace Botble\webrobotdashboard\Http\Requests;
use Botble\Support\Http\Requests\Request;
class TaskEditRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => 'required|max:120|min:2',
            'quantity' => 'required|max:120|min:2',
            'dataset' => 'required|max:120|min:2'
        ];
    }
}
