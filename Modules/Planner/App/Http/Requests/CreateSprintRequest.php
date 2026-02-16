<?php

namespace Modules\Planner\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSprintRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title'=>'required',
            'start_date'=> 'required | date',
            'end_date' => 'required | date',
            'board_id' => 'required',

        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
