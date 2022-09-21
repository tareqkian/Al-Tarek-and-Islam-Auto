<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOptionSubClassRequest extends FormRequest
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
      'option_class_id' => 'required_without:order|int|exists:option_classes,id',
      'ar.option_sub_class_title' => 'required_without:order|string|unique:option_sub_class_translations,option_sub_class_title',
      'en.option_sub_class_title' => 'required_without:order|string|unique:option_sub_class_translations,option_sub_class_title',
    ];
  }
}
