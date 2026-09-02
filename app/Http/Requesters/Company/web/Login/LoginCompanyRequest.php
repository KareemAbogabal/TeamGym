<?php

namespace App\Http\Requesters\Company\web\Login;

use Illuminate\Foundation\Http\FormRequest;

class LoginCompanyRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    public function rules(): array {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }
}