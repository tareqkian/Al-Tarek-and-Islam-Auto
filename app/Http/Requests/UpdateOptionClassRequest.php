<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOptionClassRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'id' => 'required|exists:option_classes',
      'en.option_class_title' => ['required', 'string',
        Rule::unique(
          'option_class_translations',
          'option_class_title',
        )->ignore($this->id,'option_class_id')],
      'ar.option_class_title' => ['required', 'string',
        Rule::unique(
          'option_class_translations',
          'option_class_title',
        )->ignore($this->id,'option_class_id')],
    ];
  }
}
