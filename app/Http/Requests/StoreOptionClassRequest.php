<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOptionClassRequest extends FormRequest
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
      'en.option_class_title' => 'required_without:order|string|unique:option_class_translations,option_class_title',
      'ar.option_class_title' => 'required_without:order|string|unique:option_class_translations,option_class_title'
    ];
  }
}
