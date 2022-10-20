<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOptionCategoryRequest extends FormRequest
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
      'option_sub_class_id' => 'required_without:order|int|exists:option_sub_classes,id',
      'abbreviation' => 'nullable|string|unique:option_categories',
      'en.option_category_title' => 'required_without:order|string|regex:/^[a-zA-Z0-9\!-_ ]+$/u|unique:option_category_translations,option_category_title',
      'ar.option_category_title' => 'required_without:order|string|regex:/^[كگچپژیلفقهمو ء-ي 0-9]+$/|unique:option_category_translations,option_category_title',
      'input_type' => 'required_without:order|string',
      'number_format' => 'required_if:input_type,number|regex:(0)'
    ];
  }
}
