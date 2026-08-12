<?php

namespace App\Http\Requesters\Website\web\DeleteCustomerRequests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteCustomerRequestsRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    public function rules(): array {
        return [
            'code' => ['required', 'string'],
        ];
    }
}
