<?php

namespace App\Http\Requesters\Website\Dashboard\Coach;

use Illuminate\Foundation\Http\FormRequest;

class CancelCoachRequest extends FormRequest {
  public function authorize(): bool {
    return true;
  }
  public function rules(): array {
    return [
      'assignment_id' => ['required', 'integer'],
    ];
  }
}
