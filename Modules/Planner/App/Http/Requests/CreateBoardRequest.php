<?php

namespace Modules\Planner\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBoardRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'key'         => 'required|string|max:255|unique:boards,key',
            'description' => 'nullable|string',
            'type'        => 'nullable|in:kanban,scrum',
            'owner_type'  => 'required|string|max:50',
            'parent_id'   => 'nullable|exists:boards,id',
            'owner_id'    => 'nullable|exists:users,id',
            'visibility'  => 'nullable|in:private,team,public',
            'created_by'  => 'nullable|exists:users,id',
            'is_active'   => 'nullable|boolean',
            'is_subtask_board' => 'nullable|boolean',
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
