<?php

namespace App\Http\Requesters\Website\web\ResetPassword;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    public function rules(): array {
        return [
            "password" => ["required", "min:5"],
            "password_confirmation" => ["required", "same:password"],
        ];
    }
}
