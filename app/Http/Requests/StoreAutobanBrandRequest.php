<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAutobanBrandRequest extends FormRequest
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
      'brand_image' => 'required|string',
      'en.brand_title' => 'required|string|regex:/^[a-zA-Z0-9\!-_ ]+$/u|unique:autoban_brand_translations,brand_title',
      'ar.brand_title' => 'required|string|regex:/^[كگچپژیلفقهمو ء-ي 0-9]+$/|unique:autoban_brand_translations,brand_title',
    ];
  }
}
