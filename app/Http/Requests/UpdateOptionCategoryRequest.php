<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOptionCategoryRequest extends FormRequest
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
      'abbreviation' => 'nullable|string|unique:option_categories,abbreviation,'.$this->id,
      'en.option_category_title' => [
        'required_without:order',
        'string',
          'regex:/^[a-zA-Z0-9\!-_ ]+$/u',
        Rule::unique('option_category_translations','option_category_title')
          ->ignore($this->id,'option_category_id')
      ],
      'ar.option_category_title' => [
        'required_without:order',
        'string',
          'regex:/^[كگچپژیلفقهموى ء-ي \!-_0-9]+$/',
        Rule::unique('option_category_translations','option_category_title')
          ->ignore($this->id,'option_category_id')
      ],
      'input_type' => 'required_without:order|string',
//      'number_format' => 'required_if:input_type,number|regex:(0)'
    ];
  }
}
