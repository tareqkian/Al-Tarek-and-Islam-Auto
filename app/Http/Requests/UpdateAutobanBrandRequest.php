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
      'en.brand_title' => [
        'required',
        'string',
          'regex:/^[a-zA-Z0-9\!-_ ]+$/u'.
        Rule::unique('autoban_brand_translations','brand_title')->ignore($this->id, 'autoban_brand_id')
      ],
      'ar.brand_title' => [
        'required',
        'string',
          'regex:/^[كگچپژیلفقهمو ء-ي 0-9]+$/'.
        Rule::unique('autoban_brand_translations','brand_title')->ignore($this->id, 'autoban_brand_id')
      ],
    ];
  }
}
