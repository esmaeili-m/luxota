<?php

namespace Modules\Support\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'ticket_id' => 'nullable|exists:tickets,id',
            'subject' => 'required_without:ticket_id|string|max:255',
            'message' => 'required_if:ticket_id,!=,null|nullable|string',
            'attachments' => 'nullable|array',
            'status' => 'nullable|string',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,png,webp|max:10240',
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
