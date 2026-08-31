<?php

namespace App\Http\Requesters\Company\Dashboard\Coach;

use Illuminate\Foundation\Http\FormRequest;

class CoachRequestsClientRequest extends FormRequest {
  public function authorize(): bool {
    return true;
  }
  public function rules(): array {
    return [
      'code_coach' => ['required', 'string'],
      'code_client' => ['required', 'string'],
      'reason' => ['nullable', 'string', 'max:1000'],
    ];
  }
}
