<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOptionSubClassRequest extends FormRequest
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
      'id' => 'required|exists:option_sub_classes,id',
      'option_class_id' => 'required|int|exists:option_classes,id',
      'ar.option_sub_class_title' => [
        'required',
        'string',
        Rule::unique(
          'option_sub_class_translations',
          'option_sub_class_title'
        )->ignore($this->id,'option_sub_class_id')
      ],
      'en.option_sub_class_title' => [
        'required',
        'string',
        Rule::unique(
          'option_sub_class_translations',
          'option_sub_class_title'
        )->ignore($this->id,'option_sub_class_id')
      ],
    ];
  }
}
