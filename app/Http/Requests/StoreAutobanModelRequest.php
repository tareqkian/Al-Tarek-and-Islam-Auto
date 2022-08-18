<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAutobanModelRequest extends FormRequest
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
      'data.*.model_image' => 'required|string',
      'data.*.en.model_title' => 'required|string|unique:autoban_model_translations,model_title',
      'data.*.ar.model_title' => 'required|string|unique:autoban_model_translations,model_title'
    ];
  }
}
