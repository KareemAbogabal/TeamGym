<?php

namespace App\Http\Requesters\Company\Dashboard\AddEmployee;

use Illuminate\Foundation\Http\FormRequest;

class AddEmployeeRequest extends FormRequest {
    public function rules(): array {
        return [
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'job_role' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'digits:11'],
            'img' => ['required', 'mimes:png,jpg,jpeg', 'max:5120'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'min:8'],
            'documentation' => ['required'],
        ];
    }
}