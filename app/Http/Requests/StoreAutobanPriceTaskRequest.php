<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAutobanPriceTaskRequest extends FormRequest
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
      "autoban_brand_id" => 'required|array',
      "autoban_brand_id.*" => 'required|unique:autoban_price_tasks,autoban_brand_id',
      "autoban_brand_id.*.id" => 'required|exists:autoban_brands',
      "duration" => 'required|int',
    ];
  }

}
