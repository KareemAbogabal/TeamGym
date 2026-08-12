<?php

namespace App\Http\Requesters\Website\web\AddRequestCustomer;

use Illuminate\Foundation\Http\FormRequest;

class AddRequestCustomerRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    public function rules(): array {
        return [
            'code' => ['required','array'],
            'code.*' => ['required'],
            'type' => ['required','array'],
            'type.*' => ['required'],
            'order_name' => ['required','array'],
            'order_name.*' => ['required'],
            'quantity' => ['nullable','array'],
            'quantity.*' => ['nullable'],
            "fname" => ["nullable", "string"],
            "lname" => ["nullable", "string"],
            "phone" => ["required", "string"],
            "email" => ["nullable", "email"],
        ];
    }
}
