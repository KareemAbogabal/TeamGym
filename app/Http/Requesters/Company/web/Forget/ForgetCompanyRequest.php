<?php

namespace App\Http\Requesters\Company\web\Forget;

use Illuminate\Foundation\Http\FormRequest;

class ForgetCompanyRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    public function rules(): array {
        return [
            "email" => ["required", "email"],
        ];
    }
}
