<?php

namespace App\Http\Requesters\Company\Dashboard\UpdateEmployee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    public function rules(): array {
        return [
            'code-employee' => ['required'],
            'fname-employee' => ['required', 'string'],
            'lname-employee' => ['required', 'string'],
            'email-employee' => ['required', 'email'],
            'phone-employee' => ['required', 'digits:11'],
            'password-employee' => ['nullable'],
        ];
    }
}
