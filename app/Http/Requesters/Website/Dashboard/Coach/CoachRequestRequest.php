<?php

namespace App\Http\Requesters\Website\Dashboard\Coach;

use Illuminate\Foundation\Http\FormRequest;

class CoachRequestRequest extends FormRequest {
  public function authorize(): bool {
    return true;
  }
  public function rules(): array {
    return [
      'code_coach' => ['required', 'string'],
      'reason' => ['nullable', 'string', 'max:1000'],
    ];
  }
}
