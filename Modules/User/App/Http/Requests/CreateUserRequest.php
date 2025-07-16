<?php

namespace Modules\User\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = $this->route('user');
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => $userId ? 'nullable|string|min:6' : 'required|string|min:6',
            'phone' => 'required|string|unique:users,phone,' . $userId,
            'description' => 'nullable|string',
            'avatar' => 'nullable|string',
            'website' => 'nullable|array',
            'address' => 'nullable|string|max:255',
            'luxota_website' => 'nullable|string|max:255',
            'status' => 'boolean',
            'country_code' => 'nullable|string|max:10',
            'whatsapp_number' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'zone_id' => 'required|exists:zones,id',
            'city_id' => 'required|exists:cities,id',
            'rank_id' => 'required|exists:ranks,id',
            'referrer_id' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
            'parent_id' => 'nullable|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The user name is required.',
            'name.string' => 'The name must be a valid string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            
            'email.required' => 'The email address is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'This email address is already taken.',
            
            'password.required' => 'The password is required.',
            'password.string' => 'The password must be a valid string.',
            'password.min' => 'The password must be at least 6 characters.',
            
            'phone.required' => 'The phone number is required.',
            'phone.string' => 'The phone must be a valid string.',
            'phone.unique' => 'This phone number is already taken.',
            
            'description.string' => 'The description must be a valid string.',
            
            'avatar.string' => 'The avatar must be a valid string.',
            
            'website.array' => 'The website must be a valid array.',
            
            'address.string' => 'The address must be a valid string.',
            'address.max' => 'The address may not be greater than 255 characters.',
            
            'luxota_website.string' => 'The luxota website must be a valid string.',
            'luxota_website.max' => 'The luxota website may not be greater than 255 characters.',
            
            'status.boolean' => 'The status must be true or false.',
            
            'country_code.string' => 'The country code must be a valid string.',
            'country_code.max' => 'The country code may not be greater than 10 characters.',
            
            'whatsapp_number.string' => 'The whatsapp number must be a valid string.',
            'whatsapp_number.max' => 'The whatsapp number may not be greater than 20 characters.',
            
            'role_id.required' => 'The role is required.',
            'role_id.exists' => 'The selected role is invalid.',
            
            'zone_id.required' => 'The zone is required.',
            'zone_id.exists' => 'The selected zone is invalid.',
            
            'city_id.required' => 'The city is required.',
            'city_id.exists' => 'The selected city is invalid.',
            
            'rank_id.required' => 'The rank is required.',
            'rank_id.exists' => 'The selected rank is invalid.',
            
            'referrer_id.required' => 'The referrer is required.',
            'referrer_id.exists' => 'The selected referrer is invalid.',
            
            'branch_id.required' => 'The branch is required.',
            'branch_id.exists' => 'The selected branch is invalid.',
            
            'parent_id.exists' => 'The selected parent is invalid.',
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