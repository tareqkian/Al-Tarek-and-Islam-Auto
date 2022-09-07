<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAutobanRequest extends FormRequest
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
      "autoban_model_id" => "required|int|exists:autoban_models,id",
      "autoban_type_id" => [
        "required",
        "int",
        "exists:autoban_types,id",
        Rule::unique('autobans')
          ->where('autoban_model_id',$this->autoban_model_id)
          ->where('autoban_year_id',$this->autoban_year_id)
          ->ignore($this->id,'id')
      ],
      "autoban_year_id" => "required|int|exists:autoban_years,id",
      "price_list_appearance" => "boolean",
      "market_availability" => "boolean",
    ];
  }

  /**
   * Prepare inputs for validation.
   *
   * @return void
   */
  protected function prepareForValidation()
  {
    $this->merge([
      'price_list_appearance' => $this->toBoolean($this->price_list_appearance),
      'market_availability' => $this->toBoolean($this->market_availability),
    ]);
  }

  /**
   * Convert to boolean
   *
   * @param $booleable
   * @return boolean
   */
  private function toBoolean($booleable)
  {
    return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
  }
}
