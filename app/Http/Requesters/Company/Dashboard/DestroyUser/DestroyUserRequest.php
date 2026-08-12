<?php

namespace App\Http\Requesters\Company\Dashboard\DestroyUser;

use Illuminate\Foundation\Http\FormRequest;

class DestroyUserRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    public function rules(): array {
        return [
            'id' => ['required'],
            'state' => ['required'],
        ];
    }
}
