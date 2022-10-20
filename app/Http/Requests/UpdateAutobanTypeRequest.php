<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAutobanTypeRequest extends FormRequest
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
      'en.type_title' => [
        'required', 'string', 'regex:/^[a-zA-Z0-9\!-_ ]+$/u',
        Rule::unique('autoban_type_translations','type_title')->ignore($this->id, 'autoban_type_id')
      ],
      'ar.type_title' => [
        'required', 'string', 'regex:/^[كگچپژیلفقهمو ء-ي 0-9]+$/',
        Rule::unique('autoban_type_translations','type_title')->ignore($this->id, 'autoban_type_id')
      ],
    ];
  }
}
