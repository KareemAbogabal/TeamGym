<?php

namespace App\Http\Requesters\Website\web\SignUp;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    public function rules(): array {
        return [
            "lname" => ["required", "string"],
            "fname" => ["required", "string"],
            "phone" => ["required", "numeric", "digits:11"],
            "email" => ["nullable", "email"],
            "password" => ["required", "min:8"],
        ];
    }
}
