<?php

namespace App\Http\Requesters\Company\Dashboard\UpdateClient;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    public function rules(): array {
        return [
            'code' => ['required'],
            'fname' => ['required', 'string'],
            'lname' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'digits:11'],
            'password' => ['nullable'],
            'category' => ['required'],
            'documentation' => ['required'],
        ];
    }
}
