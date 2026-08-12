<?php

namespace App\Http\Requesters\Website\web\Forget;

use Illuminate\Foundation\Http\FormRequest;

class ForgetRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    public function rules(): array {
        return [
            "email" => ["required", "email"],
        ];
    }
}
