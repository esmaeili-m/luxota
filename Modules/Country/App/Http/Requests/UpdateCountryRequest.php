<?php

namespace Modules\Country\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCountryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'en'=>'sometimes|required',
            'abb'=>'sometimes|required',
            'phone_code'=>'sometimes|required',
            'zone_id'=>'sometimes|required',
            'currency_id'=>'sometimes|required',
        ];
    }
    public function messages(): array
    {
        return [
            'en.required'          => 'The English name is required.',
            'abb.required'         => 'The abbreviation is required.',
            'phone_code.required'  => 'The phone code is required.',
            'zone_id.required'     => 'The zone field is required.',
            'currency_id.required' => 'The currency field is required.',
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
