<?php

namespace Modules\Planner\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'board_id' => ['sometimes','exists:boards,id'],
            'column_id' => ['sometimes','exists:columns,id'],
            'sprint_id' => ['nullable','exists:sprints,id'],
            'ticket_id' => ['nullable','exists:tickets,id'],

            'title_fa' => ['sometimes','string','max:255'],
            'title_en' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],

            'type' => ['sometimes','in:task,story'],
            'priority' => ['sometimes','in:low,high,critical'],
            'task_category' => ['nullable','in:bug,improvement,task,change_request'],
            'urgent' => ['boolean'],

            'parent_task_id' => ['nullable','exists:tasks,id'],
            'business_status' => ['nullable','in:client_request,infrastructure,business_dev'],
            'has_invoice' => ['nullable','in:free,invoiced,not_declared'],

            'implementation' => ['nullable','in:plugin,core'],

            'created_by' => ['nullable','exists:users,id'],
            'assigned_to' => ['nullable','exists:users,id'],

            'team_id' => ['sometimes','exists:teams,id'],

            'due_date' => ['nullable','date'],

            'status' => ['boolean'],

            'attachments' => ['nullable','array'],
            'attachments.*' => ['file','max:5120'], // حداکثر 5 مگابایت
            'attachments_titles' => ['nullable','array'],
            'attachments_titles.*' => ['required_with:attachments.*','string','max:255'],
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
