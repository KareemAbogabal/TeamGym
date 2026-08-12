<?php

namespace App\Http\Requesters\Website\Dashboard\UpdateProfile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    public function rules(): array {
        return [
            'fname' => ['required', 'string', 'max:100'],
            'lname' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['nullable'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'class_reminders' => ['nullable', 'in:on,1,true,false,0'],
            'payment_date' => ['nullable', 'in:on,1,true,false,0'],
            'promotions' => ['nullable', 'in:on,1,true,false,0'],
            'login_alerts' => ['nullable', 'in:on,1,true,false,0'],
            'action' => ['required'],
        ];
    }
}
