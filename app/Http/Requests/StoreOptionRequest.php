<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOptionRequest extends FormRequest
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
      'option_category_id' => 'required|int|exists:option_categories,id',
      'en.option_title' => 'required|string|unique:option_translations,option_title',
      'ar.option_title' => 'required|string|unique:option_translations,option_title',
      'abbreviation' => 'nullable|string|unique:options'
    ];
  }
}
