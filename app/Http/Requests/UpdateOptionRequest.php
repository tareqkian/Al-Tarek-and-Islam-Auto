<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOptionRequest extends FormRequest
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
      'en.option_title' => [
        'required',
        'string',
        Rule::unique('option_translations','option_title')
          ->ignore($this->id,'option_id')
      ],
      'ar.option_title' => [
        'required',
        'string',
        Rule::unique('option_translations','option_title')
          ->ignore($this->id,'option_id')
      ],
      'abbreviation' => 'nullable|string|unique:options,abbreviation,'.$this->id
    ];
  }
}
