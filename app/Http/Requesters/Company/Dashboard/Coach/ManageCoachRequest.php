<?php

namespace App\Http\Requesters\Company\Dashboard\Coach;

use Illuminate\Foundation\Http\FormRequest;

class ManageCoachRequest extends FormRequest {
  public function authorize(): bool {
    return true;
  }
  public function rules(): array {
    return [
      'assignment_id' => ['required', 'integer'],
      'action' => ['required', 'string', 'in:approve,reject,cancel,end'],
      'note' => ['nullable', 'string', 'max:1000'],
    ];
  }
}
