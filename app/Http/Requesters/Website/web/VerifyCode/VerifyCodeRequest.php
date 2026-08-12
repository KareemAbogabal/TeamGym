<?php

namespace App\Http\Requesters\Website\web\VerifyCode;

use Illuminate\Foundation\Http\FormRequest;

class VerifyCodeRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    public function rules(): array {
        return [
            "code" => ["required", "digits:6"],
        ];
    }
}
