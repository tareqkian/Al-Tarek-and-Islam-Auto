<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAutobanBrandRequest extends FormRequest
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
      'brand_image' => 'nullable|string',
      'brand_title' => [
        'required',
        'string',
        Rule::unique('autoban_brand_translations')->ignore($this->id, 'autoban_brand_id')
      ]
    ];
  }
}
