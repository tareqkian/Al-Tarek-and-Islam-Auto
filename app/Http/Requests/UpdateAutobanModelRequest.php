<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAutobanModelRequest extends FormRequest
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
      'autoban_brand_id' => 'required|int|exists:autoban_brands,id',
      'data.*.model_image' => 'nullable|string',
      'data.*.en.model_title' => [
        'required', 'string', 'regex:/^[a-zA-Z0-9\!-_ ]+$/u',
        Rule::unique('autoban_model_translations','model_title')->ignore($this->id, 'autoban_model_id')
      ],
      'data.*.ar.model_title' => [
        'required', 'string', 'regex:/^[كگچپژیلفقهمو ء-ي 0-9]+$/',
        Rule::unique('autoban_model_translations','model_title')->ignore($this->id, 'autoban_model_id')
      ],
    ];
  }
}
