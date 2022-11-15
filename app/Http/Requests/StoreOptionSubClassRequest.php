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
        'en.option_sub_class_title' => 'required_without:order|string|regex:/^[a-zA-Z0-9\!-_ ]+$/u|unique:option_sub_class_translations,option_sub_class_title',
        'ar.option_sub_class_title' => 'required_without:order|string|regex:/^[كگچپژیلفقهموى ء-ي \!-_0-9]+$/|unique:option_sub_class_translations,option_sub_class_title',
    ];
  }
}
