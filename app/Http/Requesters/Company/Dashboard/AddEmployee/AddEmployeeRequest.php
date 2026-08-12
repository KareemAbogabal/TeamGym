<?php

namespace App\Http\Requesters\Company\Dashboard\AddEmployee;

use Illuminate\Foundation\Http\FormRequest;

class AddEmployeeRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    public function rules(): array {
        return [
            'fname' => ['required', 'string'],
            'lname' => ['required', 'string'],
            'job_role' => ['required', 'string'],
            'phone' => ['required', 'digits:11'],
            'img' => ['required', 'mimes:png,jpg,jpeg', 'max:5120'],
            'email' => ['required', 'email'],
            'password' => ['required'],
            'documentation' => ['required'],
        ];
    }
}
