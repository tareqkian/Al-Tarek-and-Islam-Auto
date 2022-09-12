<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAutobanPriceTaskRequest extends FormRequest
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
      "id" => 'required|int|exists:autoban_price_tasks',
      "autoban_brand_id" => 'required|int|exists:autoban_price_tasks',
      "duration" => 'required|int',
    ];
  }
}
