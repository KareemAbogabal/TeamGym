<?php

namespace App\Http\Requesters\Website\web\AddRequestProduct;

use Illuminate\Foundation\Http\FormRequest;

class AddRequestProductRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    public function rules(): array {
        return [
            'code' => ['required', 'string'],
            'order_name' => ['required', 'string'],
            'amount' => ['required', 'string'],
        ];
    }
}
