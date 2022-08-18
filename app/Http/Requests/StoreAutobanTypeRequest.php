<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAutobanTypeRequest extends FormRequest
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
      'en.type_title' => 'required|string|unique:autoban_type_translations,type_title',
      'ar.type_title' => 'required|string|unique:autoban_type_translations,type_title'
    ];
  }
}
